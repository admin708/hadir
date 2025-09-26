<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusPresensiIdToPresensis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->foreignId('status_presensi_id')->default(1)->after('confirmed')->comment('1=>Hadir, 2=>Alpa, 3=>Izin, 4=>Sakit');
            $table->foreign('status_presensi_id')->references('id')->on('status_presensis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->dropColumn('status_presensi_id');
        });
    }
}
