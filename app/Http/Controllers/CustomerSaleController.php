<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductUser;
use App\Models\User;
use App\Services\InventoryTransferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerSaleController extends Controller
{
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
    ): RedirectResponse
    {
        $request->validate([
            'address' => ['required', 'string', 'max:1000'],
            'products' => ['required', 'array'],
            'discount' => ['nullable','numeric','min:0'],
        ]);

        $selected = collect($request->products)
            ->filter(fn ($q) => $q > 0);

        if ($selected->isEmpty()) {
            return back()
                ->withErrors([
                    'products' => 'حداقل یک محصول باید انتخاب شود.'
                ])
                ->withInput();
        }

        $discount = (int) $request->discount;

        $inventoryTransferService->transfer(
            fromUserId: auth()->id(),
            toUserId: $customer->id,
            products: $request->products,
            status: 'success',
            address: $request->address,
            discount: $discount
        );

        return redirect()
            ->route('admin.orders.index')
            ->with('success','سفارش ثبت شد.');
    }
}
