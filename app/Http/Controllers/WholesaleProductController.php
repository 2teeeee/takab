<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUser;
use App\Services\InventoryTransferService;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WholesaleProductController extends Controller
{
    public function index(): View
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

        foreach ($products as $product) {
            $product->stock = $this->getAvailableQuantity($product);
        }

        return view('wholesaler.products', compact('products'));
    }

    public function store(Request $request, InventoryTransferService $inventoryTransferService): RedirectResponse
    {
        $request->validate([
            'products' => 'required|array',
        ]);

        $inventoryTransferService->transfer(
            fromUserId: config('shop.company_user_id'),
            toUserId: auth()->id(),
            products: $request->products
        );

        return redirect()
            ->route('wholesaler.products')
            ->with('success', 'خرید با موفقیت ثبت شد.');
    }

    protected function inventoryOwnerId(): int
    {
        return config('shop.company_user_id');
    }

    protected function getAvailableQuantity(Product $product): int
    {
        return ProductUser::where([
            'user_id' => $this->inventoryOwnerId(),
            'product_id' => $product->id,
        ])->value('quantity') ?? 0;
    }
}