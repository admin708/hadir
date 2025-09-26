<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClockInAndClockOutAndToleransiToJadwals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->time('clock_in')->nullable()->default(NULL)->after('weekdays');
            $table->time('clock_out')->nullable()->default(NULL)->after('clock_in');
            $table->unsignedInteger('toleransi_terlambat')->default(0)->after('clock_out');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropColumn(['clock_in', 'clock_out', 'toleransi_terlambat']);
        });
    }
}
