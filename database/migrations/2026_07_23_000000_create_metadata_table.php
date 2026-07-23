<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('metadata', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('db_name', 50)->nullable();
            $table->string('schema_name', 50)->nullable();
            $table->string('tbl_name', 100)->nullable();
            $table->string('name', 100)->nullable();
            $table->string('type', 50)->nullable();
            $table->string('type_name', 50)->nullable();
            $table->text('description')->nullable();
            $table->integer('seq')->nullable();
            $table->timestamp('create_date')->nullable();
            $table->timestamp('last_update')->nullable();

            $table->index(['db_name', 'schema_name', 'tbl_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadata');
    }
};
