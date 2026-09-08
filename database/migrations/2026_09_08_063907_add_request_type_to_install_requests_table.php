<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('install_requests', function (Blueprint $table) {
            $table->enum('request_type', [
                'installation',
                'service',
                'repair',
            ])
                ->default('service')
                ->after('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('install_requests', function (Blueprint $table) {
            $table->dropColumn('request_type');
        });
    }
};
