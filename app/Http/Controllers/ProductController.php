<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function view(Request $request, CartService $cartService): View
    {
        $product = Product::find($request->id);

        $cart = $cartService->getCart();
        $quantity = $cart->items()->where('product_id', $product->id)->value('quantity') ?? 0;

        return view('product.view', compact('product', 'quantity'));
    }
}
