<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('install_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('install_schedule_id')
                ->constrained('install_schedules')
                ->cascadeOnDelete();

            $table->foreignId('installer_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->boolean('completed')
                ->default(false);

            $table->text('description')
                ->nullable();

            $table->timestamps();

            $table->unique('install_schedule_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('install_reports');
    }
};
