<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('production_requirement_id')
                ->constrained('production_requirements')
                ->restrictOnDelete();

            $table->foreignId('supplier_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->decimal('quantity', 15, 4);

            $table->string('unit', 50)->nullable();

            $table->enum('status', [
                'pending',
                'sent',
                'viewed',
                'quoted',
                'expired',
                'cancelled',
            ])->default('pending');

            $table->timestamp('requested_at')->nullable();

            $table->date('deadline')->nullable();

            $table->text('note')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index([
                'production_requirement_id',
                'supplier_id'
            ]);

            $table->index([
                'supplier_id',
                'status'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
