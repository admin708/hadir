<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBulanKeyToLiburs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('liburs', function (Blueprint $table) {
            $table->date('bulan_key')->nullable()->after('bulan_tahun');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('liburs', function (Blueprint $table) {
            $table->dropColumn('bulan_key');
        });
    }
}
