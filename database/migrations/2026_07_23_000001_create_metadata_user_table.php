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
            $table->uuid('metadata_id');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_masked')->default(false);
            $table->boolean('soft_delete')->default(false);
            $table->timestamps();

            $table->foreign('metadata_id')->references('id')->on('metadata')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadata_user');
    }
};
