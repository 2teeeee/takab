<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('installers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('address')->nullable();

            $table->unsignedInteger('experience')->nullable();

            $table->text('description')->nullable();

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'inactive',
            ])->default('pending');

            $table->text('status_note')->nullable();

            $table->timestamps();
        });

        Schema::create('installer_wholesaler', function (Blueprint $table) {
            $table->id();

            $table->foreignId('installer_id')
                ->constrained('installers')
                ->cascadeOnDelete();

            $table->foreignId('wholesaler_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'installer_id',
                'wholesaler_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installers');
    }
};
