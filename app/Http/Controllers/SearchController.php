<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request): View
    {
        $query = trim($request->input('q', ''));
        $categoryId = $request->input('category');

        // دسته‌ها برای نمایش در فیلتر
        $categories = Category::all();

        // اگر هیچ چیزی وارد نشده باشد
        if (!$query && !$categoryId) {
            return view('products.search', [
                'products' => collect(),
                'query' => '',
                'categories' => $categories,
                'selectedCategory' => null
            ]);
        }

        // تبدیل جستجو به کلمات جداگانه
        $words = array_filter(explode(' ', $query));

        // کوئری پایه
        $products = Product::query()
            ->leftJoin('product_images as image', fn (JoinClause $image) =>
                $image->on('image.product_id', '=', 'products.id')->on('image.is_main', '=', DB::raw('1'))
            )
            ->whereNotNull('slug')
            ->whereNotNull('category_id')
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where('category_id', $categoryId);
            })
            ->when($words, function ($q) use ($words) {
                $q->where(function ($sub) use ($words) {
                    foreach ($words as $word) {
                        $sub->orWhere('products.title', 'LIKE', "%{$word}%");
                    }
                });
            })
            ->get([
                'products.id',
                'products.title',
                'products.slug',
                'image.small_image_name',
                'products.main_price',
                'products.sell_price',
                'products.category_id'
            ]);

        // محاسبه امتیاز شباهت برای مرتب‌سازی
        $scored = $products->map(function ($product) use ($words) {
            $score = 0;
            foreach ($words as $word) {
                if (stripos($product->title, $word) !== false) {
                    $score += 1;
                }
            }

            return [
                'score'   => $score,
                'product' => $product
            ];
        })
            ->sortByDesc('score')
            ->pluck('product');

        return view('products.search', [
            'products' => $scored,
            'query' => $query,
            'categories' => $categories,
            'selectedCategory' => $categoryId
        ]);
    }
}
