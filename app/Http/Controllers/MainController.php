<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Slider;
use App\Services\Sms\NikSmsService;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\View\View;
use NiksmsWebserviceServiceGroup;
use NiksmsWebserviceStructAuthenticationModel;
use NiksmsWebserviceStructGroupSms;
use NiksmsWebserviceStructGroupSmsModel;

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

        $sliders = Slider::where('lang', $locale)->where('is_active',true)->get();

        return view('index', [
            'products' => $products,
            'sliders' => $sliders
        ]);
    }

    public function admin(): View
    {
        return view('admin');
    }

    public function sendTestSms(NikSmsService $sms)
    {
        $result = $sms->sendSingle('09173326706', "تست پنل پیامک");

        if ($result['error']) {
            return response()->json($result, 500);
        }

        return response()->json($result);
    }
    /*
    public function sendTestSms()
    {


        $sms = new NikSmsService();

        $result = $sms->sendGroupSms(
            numbers: ['09173326706'],
            message: 'سلام! تست ارسال پیامک نیک اس ام اس',
        );

        dd($result);
    }*/
}
