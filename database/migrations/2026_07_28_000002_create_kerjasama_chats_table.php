<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kerjasama_chats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kerjasama_id');
            $table->string('sender_role');
            $table->string('sender_name');
            $table->text('pesan');
            $table->string('attachment_path')->nullable();
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kerjasama_chats');
    }
};
