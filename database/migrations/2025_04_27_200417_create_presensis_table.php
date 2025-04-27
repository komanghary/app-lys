<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensisTable extends Migration
{
    public function up(): void
    {
        Schema::create('t_presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('identitas_id')->constrained('t_identitas')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_masuk');
            $table->string('status')->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('t_presensis');
    }
}