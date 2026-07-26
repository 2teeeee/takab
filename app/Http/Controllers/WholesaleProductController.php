<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductUser;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'products' => ['required', 'array'],
            'products.*' => ['nullable', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($request) {

            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'address' => '-',
                'discount' => 0,
                'total' => 0,
                'final_total' => 0,
            ]);

            $total = 0;
            $discount = 0;

            foreach ($request->products as $productId => $quantity) {

                if ($quantity <= 0) {
                    continue;
                }

                $product = Product::findOrFail($productId);

                $stock = $this->getAvailableQuantity($product);

                if ($quantity > $stock) {
                    throw new \Exception("موجودی {$product->translate()->title} کافی نیست.");
                }

                $price = $product->sell_price;

                $lineTotal = $price * $quantity;

                $lineDiscount = $quantity * 1000000;

                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $lineTotal,
                ]);

                ProductUser::decrease(
                    $this->inventoryOwnerId(),
                    $product->id,
                    $quantity
                );

                ProductUser::increase(
                    auth()->id(),
                    $product->id,
                    $quantity
                );

                $total += $lineTotal;
                $discount += $lineDiscount;
            }

            if ($total == 0) {
                throw new \Exception('هیچ محصولی انتخاب نشده است.');
            }

            $order->update([
                'total' => $total,
                'discount' => $discount,
                'final_total' => max(0, $total - $discount),
            ]);

        });

        return redirect()
            ->route('wholesaler.products')
            ->with('success', 'سفارش ثبت شد.');
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
        ])->value('quantity') ?? 50;
    }
}