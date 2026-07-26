<?php

namespace App\Http\Controllers;

use App\Models\ProductUser;
use App\Services\InventoryTransferService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StoreProductController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $products = ProductUser::query()
            ->where('product_user.user_id', $this->inventoryOwnerId())
            ->where('product_user.quantity', '>', 0)
            ->join('products', 'products.id', '=', 'product_user.product_id')
            ->join('product_translations as t', function ($join) use ($locale) {
                $join->on('t.product_id', '=', 'products.id')
                    ->where('t.locale', $locale);
            })
            ->join('product_images as image', function ($join) {
                $join->on('image.product_id', '=', 'products.id')
                    ->where('image.is_main', 1);
            })
            ->select([
                'products.id',
                'products.slug',
                'products.main_price',
                'products.sell_price',
                't.title',
                'image.small_image_name',
                'product_user.quantity as stock',
            ])
            ->paginate(20);

        return view('store.products', compact(
            'products'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'products' => 'required|array',
        ]);

        app(InventoryTransferService::class)->transfer(
            fromUserId: auth()->user()->registered_by,
            toUserId: auth()->id(),
            products: $request->products,
            discountPerItem: 2_000_000
        );

        return redirect()
            ->route('store.products')
            ->with('success', 'خرید با موفقیت ثبت شد.');
    }

    protected function inventoryOwnerId(): int
    {
        return auth()->user()->registered_by;
    }

}