<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->text('body');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['new', 'read', 'referred', 'closed'])->default('new');
            $table->timestamps();
        });

        Schema::create('letter_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_id')->constrained('letters')->onDelete('cascade');
            $table->foreignId('from_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('to_user_id')->constrained('users')->onDelete('cascade');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_id')->constrained('letters')->onDelete('cascade');
            $table->string('file_path');
            $table->string('file_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letters');
        Schema::dropIfExists('letter_references');
        Schema::dropIfExists('attachments');
    }
};
