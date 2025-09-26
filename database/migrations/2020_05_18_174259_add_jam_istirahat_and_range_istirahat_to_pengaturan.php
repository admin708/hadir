<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJamIstirahatAndRangeIstirahatToPengaturan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->time('jam_istirahat')->nullable()->after('jam_masuk');
            $table->string('range_istirahat')->nullable()->default('[0,0]')->after('range_masuk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->dropColumn(['jam_istirahat', 'range_istirahat']);
        });
    }
}
