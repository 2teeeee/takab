<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assembly_sections', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // مثلاً: فیلتر، پمپ، مخزن
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('assembly_section_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assembly_section_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('assembly_sections');
        Schema::dropIfExists('assembly_section_products');
    }
};
