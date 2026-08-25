<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('install_requests', function (Blueprint $table) {
            $table->id();

            // Customer who requested the service
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();

            // Wholesaler responsible for this order/service
            $table->foreignId('wholesaler_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('device_model');

            $table->string('serial_number')
                ->nullable();

            $table->text('address');

            $table->enum('status', [
                'pending',
                'scheduled',
                'installed',
                'serviced',
                'cancelled',
            ])->default('pending');

            $table->timestamp('installation_date')
                ->nullable();

            $table->timestamps();

            $table->index('wholesaler_id');
            $table->index('order_id');
        });

        Schema::create('install_schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('installer_id')
                ->constrained('installers')
                ->cascadeOnDelete();

            $table->foreignId('install_request_id')
                ->constrained('install_requests')
                ->cascadeOnDelete();

            $table->date('scheduled_date');

            $table->enum('status', [
                'waiting',
                'done',
                'cancelled',
            ])->default('waiting');

            $table->timestamps();

            $table->index([
                'installer_id',
                'scheduled_date',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('install_requests');
    }
};
