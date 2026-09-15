<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_boms', function (Blueprint $table) {
            $table->id();

            // محصول نهایی / دستگاه تولیدی
            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            // قطعه / ماده اولیه
            $table->foreignId('component_product_id')
                ->constrained('products')
                ->restrictOnDelete();

            // مقدار مورد نیاز برای تولید یک دستگاه
            $table->decimal('quantity', 15, 4);

            // واحد: عدد، متر، کیلوگرم، لیتر و ...
            $table->string('unit', 50)->nullable();

            // قیمت واحد در زمان تعریف BOM
            $table->unsignedBigInteger('unit_price')->default(0);

            $table->boolean('is_active')->default(true);

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index([
                'product_id',
                'component_product_id',
            ]);
        });

        Schema::create('product_bom_suppliers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_bom_id')
                ->constrained('product_boms')
                ->cascadeOnDelete();

            // کاربر دارای نقش supplier
            $table->foreignId('supplier_id')
                ->constrained('users')
                ->restrictOnDelete();

            // قیمت این تأمین‌کننده
            $table->unsignedBigInteger('unit_price')->default(0);

            // تأمین‌کننده پیش‌فرض
            $table->boolean('is_default')->default(false);

            $table->boolean('is_active')->default(true);

            $table->text('note')->nullable();

            $table->timestamps();

            $table->unique([
                'product_bom_id',
                'supplier_id',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_boms');
        Schema::dropIfExists('product_bom_suppliers');
    }
};
