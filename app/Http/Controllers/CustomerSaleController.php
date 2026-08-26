<?php

namespace App\Http\Controllers;

use App\Models\ProductUser;
use App\Models\User;
use App\Services\CommissionService;
use App\Services\InventoryTransferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        /*
         * Send commission SMS notifications.
         */
        $this->commissionService->sendCommissionSms($order);

        return redirect()
            ->route('store.sales.index')
            ->with('success', 'سفارش با موفقیت ثبت شد.');
    }
}
