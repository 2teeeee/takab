<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('department_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('department_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique([
                'department_id',
                'user_id',
            ]);
        });

        Schema::table('letters', function (Blueprint $table) {
            $table->foreignId('department_id')
                ->nullable()
                ->after('sender_id')
                ->constrained('departments')
                ->nullOnDelete();

            $table->index('department_id');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
