<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_withdrawal_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('wallet_id')
                ->constrained('wallets')
                ->cascadeOnDelete();

            $table->unsignedBigInteger('amount');

            $table->string('card_number', 16)->nullable();

            $table->string('account_number')->nullable();

            $table->string('sheba_number', 26)->nullable();

            $table->string('account_holder_name')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'paid',
                'rejected',
                'cancelled',
            ])->default('pending');

            $table->text('description')->nullable();

            $table->text('admin_note')->nullable();

            $table->foreignId('processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('processed_at')->nullable();

            $table->timestamp('paid_at')->nullable();

            $table->string('payment_tracking_code')->nullable();

            $table->timestamps();

            $table->index([
                'user_id',
                'status',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_withdrawal_requests');
    }
};
