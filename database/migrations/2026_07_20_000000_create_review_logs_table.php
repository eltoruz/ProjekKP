<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('kerjasama_id');
            $table->string('label');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('kerjasama_id')->references('kerjasama_id')->on('kerjasama')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_logs');
    }
};
