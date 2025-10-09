<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('small_text')->nullable();
            $table->longText('large_text')->nullable();
            $table->string('slug')->nullable();
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
            $table->decimal('main_price')->nullable();
            $table->decimal('sell_price')->nullable();
            $table->foreignId('category_id')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->index();
            $table->string('large_image_name');
            $table->string('small_image_name');
            $table->boolean('is_main');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('products_pictures');
    }
};
