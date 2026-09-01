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
        Schema::table('install_reports', function (Blueprint $table) {

            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
            ])
                ->default('pending')
                ->after('completed');

            $table->foreignId('approved_by')
                ->nullable()
                ->after('description')
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('approved_at')
                ->nullable()
                ->after('approved_by');

            $table->text('admin_note')
                ->nullable()
                ->after('approved_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('installer_report', function (Blueprint $table) {
            //
        });
    }
};
