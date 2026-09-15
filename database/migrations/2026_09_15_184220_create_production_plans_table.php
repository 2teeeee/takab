<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('production_plans', function (Blueprint $table) {
            $table->id();

            // دستگاهی که قرار است تولید شود
            $table->foreignId('product_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->date('start_date');
            $table->date('end_date');

            // تعداد تولید
            $table->unsignedInteger('quantity');

            $table->enum('status', [
                'draft',
                'planned',
                'in_progress',
                'completed',
                'cancelled',
            ])->default('draft');

            $table->text('note')->nullable();

            // ایجادکننده برنامه
            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index([
                'product_id',
                'start_date',
                'end_date',
            ]);
        });

        Schema::create('production_requirements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('production_plan_id')
                ->constrained('production_plans')
                ->cascadeOnDelete();

            $table->foreignId('product_bom_id')
                ->constrained('product_boms')
                ->restrictOnDelete();

            // قطعه / ماده اولیه
            $table->foreignId('component_product_id')
                ->constrained('products')
                ->restrictOnDelete();

            // مقدار مصرف برای یک دستگاه
            $table->decimal('required_per_unit', 15, 4);

            // کل مقدار مورد نیاز
            $table->decimal('required_quantity', 15, 4);

            // موجودی فعلی
            $table->decimal('available_quantity', 15, 4)
                ->default(0);

            // مقدار مورد نیاز برای خرید
            $table->decimal('purchase_quantity', 15, 4)
                ->default(0);

            // قیمت واحد
            $table->unsignedBigInteger('unit_price')
                ->default(0);

            // واحد
            $table->string('unit', 50)->nullable();

            $table->enum('status', [
                'pending',
                'coordinating',
                'quoted',
                'approved',
                'ordered',
                'partially_received',
                'received',
                'cancelled',
            ])->default('pending');

            $table->text('note')->nullable();

            $table->timestamps();

            $table->index([
                'production_plan_id',
                'component_product_id',
            ],
            'production_req_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_plans');
        Schema::dropIfExists('production_requirements');
    }
};
