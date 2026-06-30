<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'content',
            ]);
        });

        Schema::create('page_translations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('page_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('locale', 5); // fa | en
            $table->string('title');
            $table->text('content')->nullable();

            $table->unique(['page_id', 'locale']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_translations');

        Schema::table('pages', function (Blueprint $table) {
            $table->string('title');
            $table->text('content')->nullable();
        });

    }
};
