<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AssemblyController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(): View
    {
        $categories = Category::with(['products' => function ($q) {
            $q->where('is_assembly_enabled', true); // فقط قطعات مجاز برای اسمبلی
        }])->where('is_assembly_enabled', true)->get();

        return view('assembly.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'selected_parts' => 'required|array|min:1',
        ]);
        $selectedIds = collect($request->input('selected_parts'))->values();
        $selectedProducts = Product::whereIn('id', $selectedIds)->get();

        if ($selectedProducts->isEmpty()) {
            return back()->with('error', 'هیچ قطعه‌ای انتخاب نشده است.');
        }

        $totalPrice = $selectedProducts->sum('sell_price');

        $assembledProduct = Product::create([
            'title'           => 'دستگاه اسمبل‌شده #' . Str::random(4),
            'slug'            => 'assembled-' . Str::uuid(),
            'sell_price'      => $totalPrice,
            'main_price'      => $totalPrice,
            'is_assembled'    => true,
            'assembled_parts' => $selectedProducts->pluck('id'),
        ]);

        // افزودن محصول به سبد
        $cart = $this->cartService->addProduct($assembledProduct, 1);

        // پیدا کردن آیتم تازه اضافه‌شده در سبد
        $cartItem = $cart->items()->latest()->first();
        // افزودن اطلاعات قطعات به meta
        $cartItem->update([
            'meta' => [
                'parts' => $selectedProducts->map(fn($p) => [
                    'name' => $p->title,
                    'price' => $p->sell_price,
                ]),
            ],
        ]);

        return redirect()->route('cart.show')
            ->with('success', '✅ دستگاه اسمبل‌شده با موفقیت به سبد خرید افزوده شد.');
    }
}
