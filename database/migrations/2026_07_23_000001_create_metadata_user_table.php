<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metadata_user', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('kerjasama_id');
            $table->uuid('metadata_id');
            $table->string('user_id', 36)->nullable();
            $table->text('alasan')->nullable();
            $table->boolean('is_masked')->default(false);
            $table->boolean('soft_delete')->default(false);
            $table->timestamp('create_date')->nullable();
            $table->timestamp('last_update')->nullable();

            $table->foreign('kerjasama_id')->references('kerjasama_id')->on('kerjasama')->onDelete('cascade');
            $table->foreign('metadata_id')->references('id')->on('metadata')->onDelete('cascade');
            $table->index(['kerjasama_id', 'metadata_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadata_user');
    }
};
