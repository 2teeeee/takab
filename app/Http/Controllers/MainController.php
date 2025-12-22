<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index(Request $request): View
    {
        $locale = app()->getLocale();

        $products = Product::query()
            ->where('products.category_id', 1)
            ->join('product_translations as t', function (JoinClause $join) use ($locale) {
                $join->on('t.product_id', '=', 'products.id')
                    ->where('t.locale', '=', $locale);
            })
            ->join('product_images as image', function (JoinClause $join) {
                $join->on('image.product_id', '=', 'products.id')
                    ->where('image.is_main', 1);
            })
            ->get([
                'products.id',
                't.title',
                'products.slug',
                'image.small_image_name',
                'products.main_price',
                'products.sell_price',
            ]);

        $sliders = Slider::where('is_active',true)->get();

        return view('index', [
            'products' => $products,
            'sliders' => $sliders
        ]);
    }

    public function admin(): View
    {
        return view('admin');
    }
}
