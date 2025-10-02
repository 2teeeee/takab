<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // سفارش مربوط به کاربر
            $table->string('address');         // آدرس وارد شده
            $table->enum('status', ['pending', 'paid', 'canceled'])->default('pending'); // وضعیت سفارش
            $table->decimal('total', 15, 2);   // مبلغ کل
            $table->string('transaction_id')->nullable(); // کد رهگیری بانکی
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');              // تعداد خریداری شده
            $table->decimal('price', 15, 2);          // قیمت واحد
            $table->decimal('total', 15, 2);          // مجموع = تعداد × قیمت
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
