<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('show_in_site')->default(true)
                ->after('is_main_sale');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('show_in_site')->default(true)
                ->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('is_main_sale');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('is_main_sale');
        });
    }
};
