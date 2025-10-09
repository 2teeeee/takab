<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Intervention\Image\Image;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function view(Request $request, CartService $cartService): View
    {
        $product = Product::find($request->id);

        $cart = $cartService->getCart();
        $quantity = $cart->items()->where('product_id', $product->id)->value('quantity') ?? 0;

        return view('products.view', compact('product', 'quantity'));
    }

    public function create(): View
    {
        return view('products.form', ['product' => new Product()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'small_text' => 'nullable|string',
            'large_text' => 'nullable|string',
            'slug' => 'nullable|string|max:255',
            'keywords' => 'nullable|string',
            'description' => 'nullable|string',
            'main_price' => 'nullable|numeric',
            'sell_price' => 'nullable|numeric',
            'category_id' => 'nullable|integer',
            'images.*' => 'nullable|image|max:2048',
        ]);

        // ساخت اسلاگ در صورت خالی بودن
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);

        $product = Product::create($data);

        // آپلود تصاویر
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('products/large', $filename, 'public');

                // ساخت تصویر کوچک با intervention
                $smallPath = 'products/small/' . $filename;
                $smallImage = Image::make($image)->resize(300, 300)->encode();
                Storage::disk('public')->put($smallPath, $smallImage);

                $product->images()->create([
                    'large_image_name' => $path,
                    'small_image_name' => $smallPath,
                    'is_main' => $index === 0, // تصویر اول به عنوان اصلی
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'محصول با موفقیت ثبت شد');
    }

    public function edit(Product $product): View
    {
        return view('products.form', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'small_text' => 'nullable|string',
            'large_text' => 'nullable|string',
            'slug' => 'nullable|string|max:255',
            'keywords' => 'nullable|string',
            'description' => 'nullable|string',
            'main_price' => 'nullable|numeric',
            'sell_price' => 'nullable|numeric',
            'category_id' => 'nullable|integer',
            'images.*' => 'nullable|image|max:2048',
            'delete_images' => 'array',
        ]);

        $product->update($data);

        // حذف تصاویر انتخاب‌شده
        if ($request->delete_images) {
            foreach ($request->delete_images as $id) {
                $img = ProductImage::find($id);
                if ($img) {
                    Storage::disk('public')->delete([$img->large_image_name, $img->small_image_name]);
                    $img->delete();
                }
            }
        }

        // آپلود تصاویر جدید
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('products/large', $filename, 'public');

                $smallPath = 'products/small/' . $filename;
                $smallImage = Image::make($image)->resize(300, 300)->encode();
                Storage::disk('public')->put($smallPath, $smallImage);

                $product->images()->create([
                    'large_image_name' => $path,
                    'small_image_name' => $smallPath,
                    'is_main' => false,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'محصول با موفقیت ویرایش شد');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'محصول حذف شد.');
    }
}
