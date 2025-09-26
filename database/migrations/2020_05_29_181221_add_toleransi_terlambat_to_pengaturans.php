<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToleransiTerlambatToPengaturans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pengaturans', function (Blueprint $table) {
            $table->unsignedInteger('toleransi_terlambat')->default(0)->after('range_pulang');
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
            $table->dropColumn('toleransi_terlambat');
        });
    }
}
