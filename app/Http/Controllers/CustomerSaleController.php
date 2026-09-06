<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUser;
use App\Models\User;
use App\Services\CommissionService;
use App\Services\InventoryTransferService;
use Cassandra\Exception\ValidationException;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CustomerSaleController extends Controller
{

    protected CommissionService $commissionService;

    public function __construct(
        CommissionService $commissionService
    ) {
        $this->commissionService = $commissionService;
    }

    public function create(User $customer): View
    {
        $locale = app()->getLocale();

        $products = ProductUser::query()
            ->where('product_user.user_id',auth()->id())
            ->where('product_user.quantity','>',0)
            ->join('products','products.id','=','product_user.product_id')
            ->join('product_translations as t',function($join) use($locale){
                $join->on('t.product_id','=','products.id')
                    ->where('t.locale',$locale);
            })
            ->join('product_images as image',function($join){
                $join->on('image.product_id','=','products.id')
                    ->where('image.is_main',1);
            })
            ->select([
                'products.id',
                't.title',
                'products.sell_price',
                'image.small_image_name',
                'product_user.quantity as stock',
            ])
            ->get();

        return view(
            'store.customer-sale.form',compact('customer','products')
        );
    }

    public function store(
        Request $request,
        User $customer,
        InventoryTransferService $inventoryTransferService
    ): RedirectResponse {

        $request->validate([
            'address' => ['required', 'string', 'max:1000'],
            'products' => ['required', 'array'],
            'discount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $store = auth()->user();

        /*
         * The store's registered_by is its wholesaler.
         */
        $wholesalerId = $store->registered_by;

        /*
         * In this sales flow, the store is also the referrer.
         */
        $referrerId = $store->id;

        $selected = collect($request->products)
            ->filter(fn ($q) => (int) $q > 0);

        if ($selected->isEmpty()) {
            return back()
                ->withErrors([
                    'products' => 'حداقل یک محصول باید انتخاب شود.'
                ])
                ->withInput();
        }

        $discount = (int) $request->discount;

        $order = $inventoryTransferService->transfer(
            fromUserId: $store->id,
            toUserId: $customer->id,
            products: $request->products,
            address: $request->address,
            discount: $discount
        );

        $order->update([
            'seller_id'     => $store->id,
            'wholesaler_id' => $wholesalerId,
            'seller_role'   => 'store',
            'moaref_id'     => $referrerId,
        ]);

        $inventoryTransferService->approve($order);

        /*
         * Create commissions.
         */
        $this->commissionService->createForOrder($order);

        $this->commissionService->payOrderCommissionsToWallet($order);

        /*
         * Send commission SMS notifications.
         */
        $this->commissionService->sendCommissionSms($order);

        return redirect()
            ->route('store.sales.index')
            ->with('success', 'سفارش با موفقیت ثبت شد.');
    }

    public function createDirect(): View
    {
        $locale = app()->getLocale();

        $store = auth()->user();

        $products = ProductUser::query()
            ->where('product_user.user_id', $store->id)
            ->where('product_user.quantity', '>', 0)

            ->join(
                'products',
                'products.id',
                '=',
                'product_user.product_id'
            )

            ->join('product_translations as t', function ($join) use ($locale) {
                $join->on(
                    't.product_id',
                    '=',
                    'products.id'
                )->where(
                    't.locale',
                    $locale
                );
            })

            ->leftJoin('product_images as image', function ($join) {
                $join->on(
                    'image.product_id',
                    '=',
                    'products.id'
                )->where(
                    'image.is_main',
                    1
                );
            })

            ->select([
                'products.id',
                't.title',
                'products.sell_price',
                'image.small_image_name',
                'product_user.quantity as stock',
            ])

            ->get();

        return view(
            'store.customer-direct-sale',
            compact('products')
        );
    }

    public function storeDirect(
        Request $request,
        InventoryTransferService $inventoryTransferService
    ): RedirectResponse {

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'mobile' => [
                'required',
                'string',
                'max:11',
            ],

            'national_code' => [
                'nullable',
                'string',
                'size:10',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'products' => [
                'required',
                'array',
            ],

            'products.*' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'discount' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ]);

        $selected = collect($validated['products'])
            ->filter(fn ($quantity) => (int) $quantity > 0);

        if ($selected->isEmpty()) {
            return back()
                ->withErrors([
                    'products' => 'حداقل یک محصول باید انتخاب شود.',
                ])
                ->withInput();
        }

        $store = auth()->user();
        $wholesalerId = $store->wholesaler_id;
        $referrerId = $store->id;

        /*
         * Find existing customer by mobile.
         */
        $customer = User::query()
            ->where('mobile', $validated['mobile'])
            ->first();

        /*
         * Create customer only when
         * the mobile number does not exist.
         */
        if (!$customer) {

            $customer = User::create([
                'name' => $validated['name'],
                'mobile' => $validated['mobile'],
                'national_code' => $validated['national_code'] ?? null,
                'password' => Hash::make(
                    Str::random(16)
                ),
                'registered_by' => $store->id,
                'wholesaler_id' => $wholesalerId,
            ]);

            $customer->assignRole('user');

            $customer->update([
                'moaref_code' => $customer->generateMoarefCode(),
            ]);
        }

        $discount = (int) ($validated['discount'] ?? 0);

        /*
         * Transfer products from store to customer.
         */
        $order = $inventoryTransferService->transfer(
            fromUserId: $store->id,
            toUserId: $customer->id,
            products: $validated['products'],
            address: $validated['address'],
            discount: $discount
        );

        /*
         * Store order relations.
         */
        $order->update([
            'seller_id' => $store->id,
            'wholesaler_id' => $wholesalerId,
            'seller_role' => 'store',
            'moaref_id'     => $referrerId,
        ]);

        /*
         * Approve inventory transfer.
         */
        $inventoryTransferService->approve($order);

        /*
         * Create commissions.
         */
        $this->commissionService->createForOrder($order);

        $this->commissionService->payOrderCommissionsToWallet($order);

        /*
         * Send commission SMS.
         */
        $this->commissionService->sendCommissionSms($order);

        return redirect()
            ->route('store.sales.index')
            ->with(
                'success',
                'سفارش مشتری با موفقیت ثبت شد.'
            );
    }

    public function checkMobile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'mobile' => ['required', 'string', 'max:11'],
        ]);

        $mobile = trim($validated['mobile']);

        $user = User::query()
            ->where('mobile', $mobile)
            ->first();

        if (!$user) {
            return response()->json([
                'exists' => false,
            ]);
        }

        return response()->json([
            'exists' => true,
            'customer' => [
                'id' => $user->id,
                'name' => $user->name,
                'mobile' => $user->mobile,
            ],
        ]);
    }

    public function createCustomer(): View
    {
        $locale = app()->getLocale();

        $products = Product::query()
            ->where('products.category_id', 1)
            ->where('products.status', 1)
            ->join('product_translations as t', function (JoinClause $join) use ($locale) {
                $join->on('t.product_id', '=', 'products.id')
                    ->where('t.locale', $locale);
            })
            ->join('product_images as image', function (JoinClause $join) {
                $join->on('image.product_id', '=', 'products.id')
                    ->where('image.is_main', 1);
            })
            ->select([
                'products.id',
                'products.sell_price',
                'products.slug',
                't.title',
                'image.small_image_name',
            ])
            ->paginate(20);

        return view(
            'customer.sale.create',
            compact('products')
        );
    }

    public function storeCustomer(
        Request $request,
        InventoryTransferService $inventoryTransferService,
        CommissionService $commissionService
    ): RedirectResponse {

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'mobile' => [
                'required',
                'string',
                'max:11',
            ],

            'national_code' => [
                'nullable',
                'string',
                'size:10',
            ],

            'address' => [
                'required',
                'string',
                'max:1000',
            ],

            'products' => [
                'required',
                'array',
            ],
        ]);

        /*
         * Remove products with zero quantity.
         */
        $products = collect($validated['products'])
            ->map(fn ($quantity) => (int) $quantity)
            ->filter(fn ($quantity) => $quantity > 0);

        if ($products->isEmpty()) {
            return back()
                ->withErrors([
                    'products' => 'حداقل یک محصول را انتخاب کنید.',
                ])
                ->withInput();
        }

        $order = DB::transaction(function () use (
            $validated,
            $products,
            $inventoryTransferService,
            $commissionService
        ) {

            $storeId = config('shop.company_user_id');
            $wholesalerId = auth()->user()->wholesaler_id;
            $referrerId = auth()->user()->id;
            /*
             * Resolve customer on the server.
             */
            $customer = User::query()
                ->where('mobile', $validated['mobile'])
                ->first();

            /*
             * Create new customer if necessary.
             */
            if (!$customer) {

                $customer = User::create([
                    'name' => $validated['name'],
                    'mobile' => $validated['mobile'],
                    'national_code' => $validated['national_code'] ?? null,
                    'password' => Hash::make(
                        Str::random(32)
                    ),
                    'registered_by' => $storeId,
                    'wholesaler_id' => $wholesalerId,
                ]);

                $customer->assignRole('user');

                $customer->update([
                    'moaref_code' => $customer->generateMoarefCode(),
                ]);

            }

            /*
             * Transfer products from the authenticated user
             * to the final customer.
             */
            $order = $inventoryTransferService->transfer(
                fromUserId: $storeId,
                toUserId: $customer->id,
                products: $products->toArray(),
                discountPerItem: 1_000_000,
                address: $validated['address']
            );

            /*
             * The authenticated user is the seller/referrer.
             */
            $order->update([
                'seller_id' => $storeId,
                'wholesaler_id' => $wholesalerId,
                'seller_role' => 'user',
                'moaref_id'     => $referrerId,
            ]);

            return $order;
        });

        return redirect()
            ->route('profile.customer.sale.create', $order)
            ->with(
                'success',
                'فروش با موفقیت ثبت شد و پورسانت معرف برای شما ثبت گردید.'
            );
    }

}
