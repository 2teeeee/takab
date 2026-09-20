<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();

            $table->string('order_number')
                ->unique();

            $table->foreignId('purchase_quote_id')
                ->nullable()
                ->constrained('purchase_quotes')
                ->nullOnDelete();

            $table->foreignId('supplier_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->foreignId('production_plan_id')
                ->nullable()
                ->constrained('production_plans')
                ->nullOnDelete();

            $table->enum('status', [
                'pending',
                'sent',
                'confirmed',
                'partially_received',
                'received',
                'cancelled'
            ])->default('pending');

            $table->date('order_date')->nullable();

            $table->date('expected_delivery_date')
                ->nullable();

            $table->unsignedBigInteger('total_amount')
                ->default(0);

            $table->text('note')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index([
                'supplier_id',
                'status',
            ]);
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_order_id')
                ->constrained('purchase_orders')
                ->cascadeOnDelete();

            $table->foreignId('production_requirement_id')
                ->nullable()
                ->constrained('production_requirements')
                ->nullOnDelete();

            $table->foreignId('component_product_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->decimal('quantity', 15, 4);

            $table->decimal('received_quantity', 15, 4)
                ->default(0);

            $table->unsignedBigInteger('unit_price');

            $table->unsignedBigInteger('total_price');

            $table->string('unit', 50)->nullable();

            $table->text('note')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('purchase_order_items');
    }
};
