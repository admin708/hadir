<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRangeMasukAndRangePulangToPengaturans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->string('range_masuk')->nullable()->default('[0,0]')->after('jam_pulang');
            $table->string('range_pulang')->nullable()->default('[0,0]')->after('range_masuk');
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
            $table->dropColumn(['range_masuk', 'range_pulang']);
        });
    }
}
