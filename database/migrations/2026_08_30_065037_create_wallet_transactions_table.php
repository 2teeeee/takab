<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('wallet_id')
                ->constrained('wallets')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->enum('type', [
                'credit',
                'debit',
            ]);

            $table->unsignedBigInteger('amount');

            $table->unsignedBigInteger('balance_before');

            $table->unsignedBigInteger('balance_after');

            $table->string('description')->nullable();

            $table->nullableMorphs('reference');

            $table->string('transaction_code')
                ->unique();

            $table->timestamps();

            $table->index([
                'wallet_id',
                'created_at',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
