<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFullNameToTIdentitasTable extends Migration
{
    public function up()
    {
        Schema::table('t_identitas', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('t_identitas', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
    }
}
;