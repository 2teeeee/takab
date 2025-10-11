<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->latest()->paginate(10);

        return view('products.index', compact('products'));
    }

    public function view(Request $request, CartService $cartService): View
    {
        $product = Product::with(['images', 'mainImage'])->findOrFail($request->id);

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

        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver());

            foreach ($request->file('images') as $index => $file) {
                $filename = uniqid() . '.webp';

                $largePath = storage_path('app/public/products/large/' . $filename);
                $smallPath = storage_path('app/public/products/small/' . $filename);

                $largeIimage = $manager->read($file);
                $largeIimage->scale(1200,1200);
                $largeIimage->toWebp()->save($largePath);

                $smallImage = $manager->read($file);
                $smallImage->scale(300,300);
                $smallImage->toWebp()->save($smallPath);

                $product->images()->create([
                    'large_image_name' => 'products/large/' . $filename,
                    'small_image_name' => 'products/small/' . $filename,
                    'is_main' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'محصول با موفقیت ثبت شد');
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


        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver());

            foreach ($request->file('images') as $index => $file) {
                $filename = uniqid() . '.webp';

                $largePath = storage_path('app/public/products/large/' . $filename);
                $smallPath = storage_path('app/public/products/small/' . $filename);

                $largeIimage = $manager->read($file);
                $largeIimage->resize(1200,1200);
                $largeIimage->toWebp()->save($largePath);

                $smallImage = $manager->read($file);
                $smallImage->resize(300,300);
                $smallImage->toWebp()->save($smallPath);

                $product->images()->create([
                    'large_image_name' => 'products/large/' . $filename,
                    'small_image_name' => 'products/small/' . $filename,
                    'is_main' => false,
                ]);
            }
        }

        if ($request->main_image_id) {
            $product->images()->update(['is_main' => false]);

            $mainImage = ProductImage::find($request->main_image_id);
            if ($mainImage) {
                $mainImage->update(['is_main' => true]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'محصول با موفقیت ویرایش شد');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'محصول حذف شد.');
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $request->validate([
            'upload' => 'required|image|max:2048',
        ]);

        $file = $request->file('upload');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        $path = $file->storeAs('products/editor', $filename, 'public');

        return response()->json([
            'url' => asset('storage/' . $path)
        ]);
    }
}
