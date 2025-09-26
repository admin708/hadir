<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJamPulangCepatAndJamKerjaAndJamLemburToPresensis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->unsignedInteger('jam_pulang_cepat')->nullable()->default(0)->after('terlambat');
            $table->unsignedInteger('jam_kerja')->nullable()->default(0)->after('jam_pulang_cepat');
            $table->unsignedInteger('jam_lembur')->nullable()->default(0)->after('jam_kerja');
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
            $table->dropColumn(['jam_pulang_cepat', 'jam_kerja', 'jam_lembur']);
        });
    }
}
