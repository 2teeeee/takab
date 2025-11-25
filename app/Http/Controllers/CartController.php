<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): JsonResponse
    {
        $cart = $this->cartService->getCart();
        $cart->load('items');

        return response()->json([
            'items' => $cart->items,
            'totalQuantity' => $cart->items->count(),
        ]);
    }

    public function show(): View
    {
        $cart = $this->cartService->getCart();
        $cart->load('items.product');

        return view('cart.show', compact('cart'));
    }

    public function increase(Product $product): JsonResponse
    {
        $cart = $this->cartService->getCart();
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->quantity++;
            $item->total = $item->quantity * $item->price;
            $item->save();
        }

        return response()->json([
            'success'     => true,
            'quantity'    => $item->quantity,
            'item_total'  => number_format($item->total),
            'cart_total'  => number_format($cart->items->sum('total')),
            'cart_count'  => $cart->items->count(),
        ]);
    }

    public function decrease(Product $product): JsonResponse
    {
        $cart = $this->cartService->getCart();
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

        return response()->json([
            'success'     => true,
            'quantity'    => $item ? $item->quantity : 0,
            'item_total'  => $item ? number_format($item->total) : 0,
            'cart_total'  => number_format($cart->items->sum('total')),
            'cart_count'  => $cart->items->count(),
        ]);
    }
    public function add(Request $request, Product $product): JsonResponse
    {
        $cart = $this->cartService->addProduct($product, $request->input('quantity', 1));

        return response()->json([
            'items' => $cart->items,
            'totalQuantity' => $cart->items->count(),
        ]);
    }

    public function remove(Product $product): JsonResponse
    {
        $cart = $this->cartService->getCart();
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->delete();
        }

        return response()->json([
            'success'    => true,
            'cart_total' => number_format($cart->items->sum('total')),
            'cart_count' => $cart->items->count(),
        ]);
    }

    public function clear(): JsonResponse
    {
        $this->cartService->clear();
        return response()->json(['message' => 'Cleared']);
    }

    public function address(): View
    {
        $cart = $this->cartService->getCart();
        $cart->load('items.product');

        return view('cart.address', compact('cart'));
    }

    public function pay(Request $request): string
    {
        $request->validate([
            'address' => 'required|string|min:10',
        ]);

        $cart = $this->cartService->getCart();

        $order = Order::create([
            'user_id' => auth()->id(),
            'address' => $request->address,
            'status' => 'pending',
            'total' => $cart->items->sum('total'),
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->price,
                'total'      => $item->total,
            ]);
        }

        return redirect()->route('zarinpal.pay',['order'=> $order]);
    }

    private function calculateCartTotal(): int
    {
        $cart = app(CartService::class)->getCart();
        return $cart->items->sum('total');
    }
}
