<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductUser;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class InventoryController extends Controller
{
    public function index(User $user): View
    {
        $products = ProductUser::with('product')
            ->where('user_id', $user->id)
            ->paginate(20);

        return view('product-user.index', compact('user', 'products'));
    }

    public function create(User $user): View
    {
        $products = Product::orderBy('id')->get();

        return view('product-user.create', compact('user', 'products'));
    }

    public function store(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $inventory = ProductUser::firstOrNew([
            'user_id'    => $user->id,
            'product_id' => $request->product_id,
        ]);

        $inventory->quantity += $request->quantity;

        $inventory->save();

        return redirect()
            ->route('admin.product-user.index', $user)
            ->with('success', 'موجودی با موفقیت ثبت شد.');
    }

    public function edit(ProductUser $productUser): View
    {
        return view('product-user.edit', compact('productUser'));
    }

    public function update(Request $request, ProductUser $productUser): RedirectResponse
    {
        $request->validate([
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        $productUser->update([
            'quantity' => $request->quantity,
        ]);

        return redirect()
            ->back()
            ->with('success', 'موجودی بروزرسانی شد.');
    }

    public function destroy(ProductUser $productUser): RedirectResponse
    {
        $productUser->delete();

        return back()->with('success', 'محصول حذف شد.');
    }
}