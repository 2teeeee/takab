<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\Sms\NikSmsService;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class StoreController extends Controller
{
    public function index(): View
    {
        $orders = Order::where('moarefStore_id', Auth::id())
            ->whereIn('status', ['paid', 'canceled', 'success','pending']) // فیلتر وضعیت‌ها
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('profile.store.index', compact('orders'));
    }
    public function sell(): View
    {
        $locale = app()->getLocale();

        $products = Product::query()
            ->where('products.category_id', 1)
            ->where('products.status', 1)
            ->join('product_translations as t', function (JoinClause $join) use ($locale) {
                $join->on('t.product_id', '=', 'products.id')
                    ->where('t.locale', '=', $locale);
            })
            ->get([
                'products.id',
                't.title',
                'products.main_price',
                'products.sell_price',
            ]);

        return view('profile.store.sell', compact('products'));
    }

    public function create(Request $request, NikSmsService $sms): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:11'],
            'address' => ['required', 'string'],
            'product_id' => ['required'],
        ]);

        $user = User::where('mobile',$request->mobile)->first();
        if($user == null)
        {
            $moarefCode = 'm'.rand(111111,999999);
            $user = User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'password' => Hash::make($request->mobile),
                'moaref_code' => $moarefCode,
                'moaref_id' => auth()->id()
            ]);

            $sms->sendSingle($request->mobile, "به جمع تک آبی ها خوش آمدید."."\n"."کد معرف شما:".$moarefCode);
        }

        $product = Product::find($request->product_id);
        if($request->moaref != null)
            $moaref = User::where('moaref_code', $request->moaref)->first();

        $order = Order::create([
            'user_id' => $user->id,
            'address' => $request->address,
            'status' => 'success',
            'total' => $product->sell_price,
            'moarefStore_id' => auth()->id(),
            'moaref_id' => $moaref ? $moaref->id : null,
        ]);

        $order->items()->create([
            'product_id' => $product->id,
            'quantity'   => 1,
            'price'      => $product->sell_price,
            'total'      => $product->sell_price,
        ]);

        return redirect()->route('profile.store.index')->with('success', 'اطلاعات با موفقیت به‌روزرسانی شد.');
    }
}
