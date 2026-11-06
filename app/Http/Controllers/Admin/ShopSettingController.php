<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShopSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopSettingController extends Controller
{
    /**
     * نمایش فرم تنظیمات
     */
    public function index()
    {
        $settings = ShopSetting::whereIn('key', [
            'wholesaler_commission',
            'store_commission',
            'referral_commission',
            'customer_discount',
        ])->pluck('value', 'key');

        return view('admin.settings.commissions', compact('settings'));
    }

    /**
     * ذخیره تنظیمات
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'wholesaler_commission' => ['required', 'integer', 'min:0'],
            'store_commission'     => ['required', 'integer', 'min:0'],
            'referral_commission'  => ['required', 'integer', 'min:0'],
            'installer_commission'  => ['required', 'integer', 'min:0'],
            'customer_discount'    => ['required', 'integer', 'min:0'],
        ], [
            '*.required' => 'وارد کردن مبلغ الزامی است.',
            '*.integer'  => 'مبلغ باید عدد صحیح باشد.',
            '*.min'      => 'مبلغ نمی‌تواند منفی باشد.',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated as $key => $value) {
                ShopSetting::where('key', $key)->update([
                    'value' => $value,
                ]);
            }
        });

        return redirect()
            ->route('admin.settings.commissions')
            ->with('success', 'تنظیمات با موفقیت ذخیره شد.');
    }
}
