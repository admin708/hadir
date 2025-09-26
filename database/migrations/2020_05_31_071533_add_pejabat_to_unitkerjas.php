<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPejabatToUnitkerjas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('unitkerjas', function (Blueprint $table) {
            $table->string('nip', 20)->after('nama');
            $table->string('pejabat')->after('nip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('unitkerjas', function (Blueprint $table) {
            $table->dropColumn(['nip', 'pejabat']);
        });
    }
}
