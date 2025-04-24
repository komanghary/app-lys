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
            $table->dateTime('completed_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('t_tasks', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });
    }
};
