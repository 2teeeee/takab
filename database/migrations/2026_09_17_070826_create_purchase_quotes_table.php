<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_quotes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_request_id')
                ->constrained('purchase_requests')
                ->cascadeOnDelete();

            $table->foreignId('supplier_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('available_quantity', 15, 4);

            $table->unsignedBigInteger('unit_price');

            $table->unsignedBigInteger('total_price');

            $table->unsignedInteger('delivery_days')->nullable();

            $table->text('note')->nullable();

            $table->timestamp('quoted_at');

            $table->timestamps();

            $table->index([
                'purchase_request_id',
                'supplier_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_quotes');
    }
};
