<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->boolean('is_assembly_enabled')->default(false)->after('description');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_assembly_enabled')->default(false)->after('category_id');
        });
    }
    public function down(): void
    {
        //
    }
};
