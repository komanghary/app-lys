<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('t_tasks', function (Blueprint $table) {
            $table->string('file_done')->nullable()->after('keterangan'); // sesuaikan posisi kalau perlu
        });
    }

    public function down(): void
    {
        Schema::table('t_tasks', function (Blueprint $table) {
            $table->dropColumn('file_done');
        });
    }
};
