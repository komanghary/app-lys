<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateDefaultValueRevisiOnTTasks extends Migration
{
    public function up()
    {
        Schema::table('t_tasks', function (Blueprint $table) {
            $table->unsignedBigInteger('revisi')->default(0)->change();
        });
    }


}
;
