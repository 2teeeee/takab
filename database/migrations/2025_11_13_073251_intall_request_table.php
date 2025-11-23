<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول درخواست نصب دستگاه
        Schema::create('install_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // کاربر درخواست‌دهنده
            $table->string('device_model');
            $table->string('serial_number')->nullable();
            $table->text('address');
            $table->enum('status', ['pending', 'scheduled', 'installed', 'serviced', 'cancelled'])->default('pending');
            $table->timestamp('installation_date')->nullable();
            $table->timestamps();
        });

        // جدول برنامه‌ نصب روزانه نصاب‌ها
        Schema::create('install_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('install_request_id')->constrained('install_requests')->onDelete('cascade');
            $table->date('scheduled_date');
            $table->enum('status', ['waiting', 'done', 'cancelled'])->default('waiting');
            $table->timestamps();
        });

        // جدول سرویس‌های دوره‌ای
        Schema::create('periodic_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('install_request_id')->constrained('install_requests')->onDelete('cascade');
            $table->date('last_service_date')->nullable();
            $table->date('next_service_date')->nullable(); // 6 ماه بعد
            $table->boolean('notified')->default(false);
            $table->timestamps();
        });

    }

   public function down(): void
    {
        Schema::dropIfExists('install_requests');
        Schema::dropIfExists('install_schedules');
        Schema::dropIfExists('periodic_services');

    }
};
