<?php

namespace App\Services;

use App\Models\Cart;
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
                'status' => 'active',
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
            $item->total = $item->quantity * $item->price;
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
                'total' => $quantity * $product->price,
            ]);
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
