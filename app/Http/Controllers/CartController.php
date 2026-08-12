<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
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

    public function pay(Request $request): RedirectResponse
    {
        $request->validate([
            'address' => ['required', 'string', 'max:1000'],
            'phone' => ['required', 'string', 'max:20'],
            'postal_code' => ['required', 'string', 'max:20'],
            'referral_code' => ['nullable', 'string', 'max:50'],
        ]);

        $cart = $this->cartService->getCart();
        $cart->load('items');

        if ($cart->items->isEmpty()) {
            return back()->with('error', __('cart.empty'));
        }

        $total = $cart->items->sum('total');

        $discount = 0;
        $referrer = null;

        /*
         * Validate referral code
         */
        if ($request->filled('referral_code')) {

            $referrer = User::where(
                'moaref_code',
                trim($request->referral_code)
            )->first();

            if (!$referrer) {
                return back()
                    ->withErrors([
                        'referral_code' => __('order.invalid_referral_code'),
                    ])
                    ->withInput();
            }

            /*
             * Customer cannot use their own referral code.
             */
            if ($referrer->id === auth()->id()) {
                return back()
                    ->withErrors([
                        'referral_code' => __('order.self_referral_not_allowed'),
                    ])
                    ->withInput();
            }

            /*
             * Fixed referral discount.
             */
            $discount = 1_000_000;
        }

        $finalTotal = max(0, $total - $discount);

        $order = Order::create([
            'user_id'      => auth()->id(),
            'address'      => $request->address,
            'status'       => 'pending',

            'total'        => $total,
            'discount'     => $discount,
            'final_total'  => $finalTotal,

            'moaref_id'    => $referrer?->id,

            'postal_code'  => $request->postal_code,
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->price,
                'total'      => $item->total,
            ]);
        }

        return redirect()->route('zarinpal.pay', [
            'order' => $order,
        ]);
    }

    private function calculateCartTotal(): int
    {
        $cart = app(CartService::class)->getCart();
        return $cart->items->sum('total');
    }

    public function checkReferral(Request $request): JsonResponse
    {
        $request->validate([
            'referral_code' => ['required', 'string', 'max:50'],
        ]);

        $referrer = User::where(
            'moaref_code',
            trim($request->referral_code)
        )->first();

        if (!$referrer) {
            return response()->json([
                'success' => false,
                'message' => __('order.invalid_referral_code'),
            ], 422);
        }

        if ($referrer->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => __('order.self_referral_not_allowed'),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => __('order.referral_code_applied'),
            'discount' => 1_000_000,
            'referrer_name' => $referrer->name,
        ]);
    }
}
