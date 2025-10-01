<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
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
        return response()->json($cart->load('items'));
    }

    public function add(Request $request, Product $product): JsonResponse
    {
        $cart = $this->cartService->addProduct($product, $request->input('quantity', 1));
        return response()->json($cart->load('items'));
    }

    public function remove(Product $product): JsonResponse
    {
        $this->cartService->removeProduct($product->id);
        return response()->json(['message' => 'Removed']);
    }

    public function clear(): JsonResponse
    {
        $this->cartService->clear();
        return response()->json(['message' => 'Cleared']);
    }
}
