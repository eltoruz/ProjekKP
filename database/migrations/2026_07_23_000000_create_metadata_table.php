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
            $table->string('db_name', 100);
            $table->string('schema_name', 100);
            $table->string('tbl_name', 100);
            $table->string('name', 100);
            $table->string('type', 100);
            $table->string('type_name', 100)->nullable();
            $table->integer('type_length')->nullable();
            $table->integer('type_precision')->nullable();
            $table->integer('type_scale')->nullable();
            $table->string('type_collation', 100)->nullable();
            $table->boolean('nullable')->default(true);
            $table->boolean('primary_key')->default(false);
            $table->boolean('autoincrement')->default(false);
            $table->boolean('is_unique')->default(false);
            $table->string('default_value')->nullable();
            $table->text('description')->nullable();
            $table->integer('seq')->nullable();
            $table->dateTime('create_date')->nullable();
            $table->dateTime('last_update')->nullable();
            $table->dateTime('expired_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('metadata');
    }
};
