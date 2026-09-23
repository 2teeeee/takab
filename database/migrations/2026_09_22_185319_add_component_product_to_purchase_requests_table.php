<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchase_requests', function (Blueprint $table) {

            $table->foreignId('component_product_id')
                ->nullable()
                ->after('production_requirement_id')
                ->constrained('products')
                ->restrictOnDelete();

            $table->foreignId('production_requirement_id')
                ->nullable()
                ->change();

            $table->index([
                'supplier_id',
                'component_product_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('purchase_requests', function (Blueprint $table) {
            //
        });
    }
};
