<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table) {

            $table->enum('status', [
                'pending',
                'approved',
                'paid',
                'cancelled',
            ])
                ->default('pending')
                ->after('amount');

            $table->timestamp('paid_at')
                ->nullable()
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('referral_commissions', function (Blueprint $table) {
            //
        });
    }
};
