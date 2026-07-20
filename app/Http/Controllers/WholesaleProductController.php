<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ProductUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WholesaleProductController extends Controller
{
    public function index(CartService $cartService): View
    {
        $locale = app()->getLocale();

        $products = Product::query()
            ->where('products.category_id', 1)
            ->where('products.status', 1)
            ->join('product_translations as t', function (JoinClause $join) use ($locale) {
                $join->on('t.product_id', '=', 'products.id')
                    ->where('t.locale', '=', $locale);
            })
            ->join('product_images as image', function (JoinClause $join) {
                $join->on('image.product_id', '=', 'products.id')
                    ->where('image.is_main', 1);
            })
            ->select([
                'products.id',
                't.title',
                'products.slug',
                'image.small_image_name',
                'products.main_price',
                'products.sell_price',
            ])
            ->paginate(20);

        $cart = $cartService->getCart();

        $cartItems = $cart->items()
            ->pluck('quantity', 'product_id')
            ->toArray();

        return view('wholesaler.products', compact('products', 'cartItems'));
    }

    /**
     * افزودن محصول به سبد خرید
     */
    public function addToCart(
        Request $request,
        Product $product,
        CartService $cartService
    ): RedirectResponse {

        $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cartService->addProduct(
            $product,
            $request->quantity
        );

        return back()->with(
            'success',
            'محصول به سبد خرید اضافه شد.'
        );
    }

    /**
     * نمایش سبد خرید
     */
    public function cart(
        CartService $cartService
    ): View {

        $cart = $cartService->getCart();
        $discount = $cartService->getWholesaleDiscount();
        $total = $cart->items->sum('total');
        $final = max(0, $total - $discount);

        return view(
            'wholesaler.cart',
            compact('cart','discount','total','final')
        );
    }

    /**
     * ثبت سفارش
     */
    public function checkout(CartService $cartService): RedirectResponse
    {
        $cart = $cartService->getCart();
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return back()->with('error', 'سبد خرید خالی است.');
        }

        DB::transaction(function () use ($cart, $cartService) {

            $discount = $cartService->getWholesaleDiscount();
            $total = $cart->items->sum('total');
            $final = max(0, $total - $discount);

            $order = Order::create([
                'user_id'     => auth()->id(),
                'status'      => 'pending',
                'address'     => '-',
                'total'       => $total,
                'discount'    => $discount,
                'final_total' => $final,
            ]);

            foreach ($cart->items as $item) {

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'price'      => $item->price,
                    'total'      => $item->total,
                ]);

                ProductUser::increase(
                    auth()->id(),
                    $item->product_id,
                    $item->quantity
                );
            }

            $cartService->clear();

        });

        return redirect()
            ->route('wholesaler.products')
            ->with('success', 'سفارش با موفقیت ثبت شد.');
    }

    public function increase(Product $product, CartService $cartService): RedirectResponse
    {
        $cart = $cartService->getCart();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->quantity++;
            $item->total = $item->quantity * $item->price;
            $item->save();
        }

        return back();
    }

    public function decrease(Product $product, CartService $cartService): RedirectResponse
    {
        $cart = $cartService->getCart();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            if ($item->quantity > 1) {
                $item->quantity--;
                $item->total = $item->quantity * $item->price;
                $item->save();
            } else {
                $item->delete();
            }
        }

        return back();
    }

    public function remove(Product $product, CartService $cartService): RedirectResponse
    {
        $cart = $cartService->getCart();

        $cart->items()
            ->where('product_id', $product->id)
            ->delete();

        return back()->with('success','محصول حذف شد.');
    }
}