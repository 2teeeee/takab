<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Product;
use App\Models\ProductImage;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ایجاد نقش‌ها
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole  = Role::firstOrCreate(['name' => 'user']);

        // ایجاد کاربران
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'mobile' => '09120000000',
                'password' => Hash::make('123456'),
            ]
        );

        $user = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Normal User',
                'mobile' => '09121111111',
                'password' => Hash::make('654321'),
            ]
        );

        // اتصال نقش‌ها
        $admin->roles()->syncWithoutDetaching([$adminRole->id]);
        $user->roles()->syncWithoutDetaching([$userRole->id]);

        // ایجاد محصول نمونه
        $product = Product::firstOrCreate([
            'title' => 'نمونه محصول تستی',
        ], [
            'small_text' => 'توضیح کوتاه برای محصول نمونه',
            'large_text' => 'این محصول جهت تست سیستم فروشگاه ایجاد شده است.',
            'slug' => 'sample-product',
            'keywords' => 'تست,محصول',
            'description' => 'توضیحات سئو برای محصول تستی',
            'main_price' => 250000,
            'sell_price' => 199000,
            'category_id' => null,
        ]);

        // افزودن عکس به محصول
        ProductImage::firstOrCreate([
            'product_id' => $product->id,
            'large_image_name' => '5a922158f1fa7c244d36e30a27a57ee8f320328b_1733143048.webp',
            'small_image_name' => '5a922158f1fa7c244d36e30a27a57ee8f320328b_1733143048.webp',
            'is_main' => true,
        ]);
    }
}
