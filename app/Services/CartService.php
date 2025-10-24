<?php

namespace App\Services;

use App\Enums\StatusCarts;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartService
{
    public function getCart(): Cart
    {
        $cart = Cart::query()
            ->where('status', 'active')
            ->when(Auth::check(), fn($q) => $q->where('user_id', Auth::id()))
            ->when(!Auth::check(), fn($q) => $q->where('session_id', session()->getId()))
            ->first();

        if (! $cart) {
            $cart = Cart::create([
                'user_id' => Auth::id(),
                'session_id' => session()->getId(),
                'status' => StatusCarts::Active,
            ]);
        }

        return $cart;
    }

    public function addProduct($product, $quantity = 1): Cart
    {
        $cart = $this->getCart();

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->quantity += $quantity;

            if ($item->quantity <= 0) {
                $item->delete();
            } else {
                $item->total = $item->quantity * $item->price;
                $item->save();
            }
        } else {
            if ($quantity > 0) {
                $meta = null;

                // اگر محصول اسمبل‌شده است، قطعاتش را در meta ذخیره کن
                if ($product->is_assembled && $product->assembled_parts) {
                    $parts = Product::whereIn('id', $product->assembled_parts)->get(['id', 'title', 'sell_price']);
                    $meta = [
                        'parts' => $parts->map(fn($p) => [
                            'id' => $p->id,
                            'name' => $p->title,
                            'price' => $p->sell_price,
                        ])->toArray()
                    ];
                }

                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'price'      => $product->sell_price,
                    'total'      => $quantity * $product->sell_price,
                    'meta'       => $meta ? json_encode($meta) : null,
                ]);
            }
        }

        return $cart->refresh();
    }

    public function removeProduct($productId): void
    {
        $cart = $this->getCart();
        $cart->items()->where('product_id', $productId)->delete();
    }

    public function clear(): void
    {
        $cart = $this->getCart();
        $cart->items()->delete();
    }
}
