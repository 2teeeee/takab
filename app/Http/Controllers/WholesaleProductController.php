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

        if (!$product->status) {
            return back()->with('error','محصول غیرفعال است.');
        }

        $availableQuantity = $this->getAvailableQuantity($product);

        if($request->quantity > $availableQuantity){
            return back()->with('error','موجودی کافی نیست.');
        }

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

                ProductUser::decrease(
                    $this->inventoryOwnerId(),
                    $item->product_id,
                    $item->quantity
                );

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

    public function updateQuantity(
        Request $request,
        Product $product,
        CartService $cartService
    ): RedirectResponse {

        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $cart = $cartService->getCart();

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        // موجودی قابل خرید
        $availableQuantity = $this->getAvailableQuantity($product);

        // حذف
        if ($request->quantity == 0) {
            if ($item) {
                $item->delete();
            }
            return back()->with('success', 'محصول حذف شد.');
        }

        // بررسی موجودی
        if ($request->quantity > $availableQuantity) {
            return back()->with(
                'error',
                "حداکثر موجودی قابل خرید {$availableQuantity} عدد است."
            );
        }

        // محصول داخل سبد نیست
        if (!$item) {
            $cartService->addProduct($product, $request->quantity);
        } else {
            $item->quantity = $request->quantity;
            $item->total = $item->price * $request->quantity;
            $item->save();
        }

        return back()->with('success', 'سبد خرید بروزرسانی شد.');
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
        ])->value('quantity') ?? 1000;
    }
}