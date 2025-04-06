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
        Schema::create('t_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained("users");
            $table->string('keterangan');
            $table->datetime('deadline');
            $table->boolean("revisi")->default(false)->comment("Apakah task ini sudah direvisi?");
            $table->boolean("status")->default(false)->comment("Apakah task ini sudah selesai?");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_tasks');
    }
};