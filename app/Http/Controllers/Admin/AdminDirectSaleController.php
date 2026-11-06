<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductUser;
use App\Models\User;
use App\Models\ShopSetting;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminDirectSaleController extends Controller
{
    /**
     * نمایش فرم فروش مستقیم
     */
    public function create(): View
    {
        $locale = app()->getLocale();
        $authUser = Auth::user();

        $companyUserId = config('shop.company_user_id');

        if (!$companyUserId) {
            abort(500, 'شناسه انبار مرکزی در تنظیمات shop تعریف نشده است.');
        }

        // مشتریان
        $customers = User::query()
            ->select('id', 'name', 'mobile')
            ->where('registered_by', $authUser->id)
            ->orderBy('name')
            ->get();

        // محصولات دارای موجودی در انبار مرکزی
        $products = Product::query()
            ->join('product_user', 'products.id', '=', 'product_user.product_id')
            ->join('product_translations as t', function (JoinClause $join) use ($locale) {
                $join->on('t.product_id', '=', 'products.id')
                    ->where('t.locale', $locale);
            })
            ->where('product_user.user_id', $companyUserId)
            ->where('product_user.quantity', '>', 0)
            ->where('products.status', '=', 1)
            ->where('products.is_main_sale', 1)
            ->select([
                'products.id',
                't.title',
                'products.sell_price',
                'product_user.quantity as stock_quantity',
            ])
            ->orderBy('t.title')
            ->get();

        $productOptions = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'price' => (float) $product->sell_price,
                'stock' => (int) $product->stock_quantity,
            ];
        })->values()->all();

        return view('admin.direct-sales.create', compact(
            'customers',
            'products',
            'productOptions'
        ));
    }

    /**
     * ثبت فروش مستقیم
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:users,id'],

            'address' => ['required', 'string', 'max:255'],

            'referral_code' => ['nullable', 'string', 'max:50'],

            'payment_type' => [
                'required',
                'in:cash,cash_on_delivery',
            ],

            'items' => ['required', 'array', 'min:1'],

            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $companyUserId = config('shop.company_user_id');

        if (!$companyUserId) {
            return back()
                ->withInput()
                ->with('error', 'شناسه انبار مرکزی در تنظیمات تعریف نشده است.');
        }

        try {
            $order = DB::transaction(function () use (
                $validated,
                $companyUserId
            ) {

                /*
                 * 1. دریافت مشتری
                 */
                $customer = User::query()
                    ->lockForUpdate()
                    ->findOrFail($validated['customer_id']);

                /*
                 * 2. بررسی کد معرف
                 */
                $referrer = null;
                $discount = 0;

                if (!empty($validated['referral_code'])) {

                    $referrer = User::query()
                        ->where('moaref_code', trim($validated['referral_code']))
                        ->first();

                    if (!$referrer) {
                        throw ValidationException::withMessages([
                            'referral_code' => 'کد معرف معتبر نیست.',
                        ]);
                    }

                    if ($referrer->id === $customer->id) {
                        throw ValidationException::withMessages([
                            'referral_code' => 'مشتری نمی‌تواند معرف خودش باشد.',
                        ]);
                    }

                }

                /*
                 * 3. تعیین فروشنده و عمده‌فروش
                 */
                $sellerId = null;
                $wholesalerId = null;

                if ($referrer) {

                    $lastOrder = Order::query()
                        ->where('moaref_id', $referrer->id)
                        ->whereNotNull('seller_id')
                        ->latest('id')
                        ->first();

                    if ($lastOrder) {
                        $sellerId = $lastOrder->seller_id;

                        $seller = User::find($sellerId);

                        $wholesalerId = $seller?->wholesaler_id;
                    }
                }

                if (!$sellerId) {
                    $sellerId = $companyUserId;
                }

                /*
                 * 4. بررسی محصولات و موجودی
                 */
                $total = 0;
                $orderItems = [];

                foreach ($validated['items'] as $item) {

                    $productId = $item['product_id'];
                    $quantity = (int) $item['quantity'];

                    $product = Product::query()
                        ->whereKey($productId)
                        ->lockForUpdate()
                        ->firstOrFail();

                    if ($quantity < 1) {
                        throw ValidationException::withMessages([
                            'items' => 'تعداد محصول نامعتبر است.',
                        ]);
                    }

                    /*
                     * موجودی انبار مرکزی
                     */
                    $inventory = ProductUser::query()
                        ->where('user_id', $companyUserId)
                        ->where('product_id', $productId)
                        ->lockForUpdate()
                        ->first();

                    if (!$inventory || $inventory->quantity < $quantity) {
                        throw ValidationException::withMessages([
                            'items' => "موجودی محصول «{$product->title}» کافی نیست.",
                        ]);
                    }

                    $price = (int) $product->sell_price;
                    $itemTotal = $price * $quantity;

                    $total += $itemTotal;

                    $orderItems[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $itemTotal,
                    ];

                    // کاهش موجودی در همان تراکنش
                    $inventory->quantity -= $quantity;
                    $inventory->save();
                }

                $quantity = collect($orderItems)->sum('quantity');

                $discountPerProduct = ShopSetting::getValue('customer_discount');

                $discount = $referrer
                    ? $quantity * $discountPerProduct
                    : 0;

                /*
                 * 5. محاسبه مبلغ نهایی
                 */
                $finalTotal = max(0, $total - $discount);

                /*
                 * 6. تعیین وضعیت پرداخت
                 */
                $isPaid = $validated['payment_type'] === 'cash';

                /*
                 * 7. ثبت سفارش
                 */
                $order = Order::create([
                    'user_id' => $customer->id,

                    // انبار مرکزی
                    'from_user_id' => $companyUserId,

                    'seller_id' => $sellerId,
                    'seller_role' => 'admin',

                    'wholesaler_id' => $wholesalerId,

                    'moaref_id' => $referrer?->id,

                    'address' => $validated['address'],

                    'status' => $isPaid ? 'paid' : 'pending',

                    'payment_status' => $isPaid ? 'paid' : 'unpaid',

                    'payment_type' => $validated['payment_type'],

                    'total' => $total,
                    'discount' => $discount,
                    'final_total' => $finalTotal,
                ]);

                /*
                 * 8. ثبت اقلام سفارش
                 */
                $order->items()->createMany($orderItems);

                return $order;
            });

        } catch (ValidationException $e) {
            throw $e;

        } catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with('error', 'ثبت فروش انجام نشد. لطفاً دوباره تلاش کنید.');
        }

        return redirect()
            ->route('admin.direct-sales.create')
            ->with('success', "فروش با شماره سفارش {$order->id} با موفقیت ثبت شد.");
    }
}
