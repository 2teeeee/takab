<?php

namespace Database\Seeders;

use App\Models\ShopSetting;
use Illuminate\Database\Seeder;

class ShopSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'wholesaler_commission',
                'title' => 'کمیسیون عمده‌فروش',
                'value' => 1000000,
            ],
            [
                'key' => 'store_commission',
                'title' => 'کمیسیون فروشگاه',
                'value' => 2000000,
            ],
            [
                'key' => 'referral_commission',
                'title' => 'کمیسیون معرف',
                'value' => 1000000,
            ],
            [
                'key' => 'customer_discount',
                'title' => 'تخفیف مشتری',
                'value' => 1000000,
            ],
        ];

        foreach ($settings as $setting) {
            ShopSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}