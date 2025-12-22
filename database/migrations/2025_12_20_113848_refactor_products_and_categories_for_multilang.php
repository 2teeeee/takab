<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'keywords',
                'description',
            ]);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'small_text',
                'large_text',
                'slug',
                'keywords',
                'description',
            ]);
        });

        Schema::create('category_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('locale', 5); // fa | en
            $table->string('title');
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();

            $table->unique(['category_id', 'locale']);
            $table->timestamps();
        });

        Schema::create('product_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('locale', 5); // fa | en
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('small_text')->nullable();
            $table->longText('large_text')->nullable();
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();

            $table->unique(['product_id', 'locale']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_translations');
        Schema::dropIfExists('category_translations');

        Schema::table('categories', function (Blueprint $table) {
            $table->string('title');
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->string('title');
            $table->text('small_text')->nullable();
            $table->longText('large_text')->nullable();
            $table->string('slug')->nullable();
            $table->text('keywords')->nullable();
            $table->text('description')->nullable();
        });
    }
};
