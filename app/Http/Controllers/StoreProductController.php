<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductUser;
use App\Services\CartService;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class StoreProductController extends Controller
{
    public function index(CartService $cartService): View
    {
        $locale = app()->getLocale();

        $products = ProductUser::query()
            ->where('product_user.user_id', auth()->user()->wholesaler_id)
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

        $cart = $cartService->getCart();

        $cartItems = $cart->items()
            ->pluck('quantity', 'product_id')
            ->toArray();

        return view('store.products', compact(
            'products',
            'cartItems'
        ));
    }

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
        $discount = $cartService->getStoreDiscount();
        $total = $cart->items->sum('total');
        $final = max(0, $total - $discount);

        return view(
            'store.cart',
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

            $discount = $cartService->getStoreDiscount();

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

                // کاهش موجودی عمده فروش
                ProductUser::decrease(
                    auth()->user()->wholesaler_id,
                    $item->product_id,
                    $item->quantity
                );

                // افزایش موجودی فروشگاه
                ProductUser::increase(
                    auth()->id(),
                    $item->product_id,
                    $item->quantity
                );
            }

            $cartService->clear();

        });

        return redirect()
            ->route('store.products')
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
        return auth()->user()->registered_by;
    }

    protected function getAvailableQuantity(Product $product): int
    {
        return ProductUser::where([
            'user_id' => $this->inventoryOwnerId(),
            'product_id' => $product->id,
        ])->value('quantity') ?? 1000;
    }
}