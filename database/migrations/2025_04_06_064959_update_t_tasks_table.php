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
            $table->string('file')->nullable()->after('status'); // Replace 'column_name' with the column after which you want to add 'file'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_tasks', function (Blueprint $table) {
            $table->dropColumn('file');
        });
    }
};