<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('metadata', function (Blueprint $table) {
            $table->index(['db_name', 'tbl_name', 'seq'], 'idx_metadata_db_tbl_seq');
        });

        Schema::table('metadata_user', function (Blueprint $table) {
            $table->index(['kerjasama_id', 'approval_status'], 'idx_metauser_ks_status');
            $table->index(['metadata_id', 'kerjasama_id'], 'idx_metauser_meta_ks');
        });
    }

    public function down(): void
    {
        Schema::table('metadata', function (Blueprint $table) {
            $table->dropIndex('idx_metadata_db_tbl_seq');
        });

        Schema::table('metadata_user', function (Blueprint $table) {
            $table->dropIndex('idx_metauser_ks_status');
            $table->dropIndex('idx_metauser_meta_ks');
        });
    }
};
