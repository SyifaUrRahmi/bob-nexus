<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            // Tambahkan kolom session_number bertipe integer, beri nilai default 1, posisikan setelah round_setting_id (opsional)
            $table->integer('session_2number')->default(1)->after('round_setting_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            // Untuk menghapus kolom jika migration di-rollback
            $table->dropColumn('session_number');
        });
    }
};
