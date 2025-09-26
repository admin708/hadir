<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePivotJadwalUnitkerjaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jadwal_unitkerja', function (Blueprint $table) {
            $table->unsignedBigInteger('unitkerja_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->foreign('unitkerja_id')->references('id')->on('unitkerjas')->onDelete('cascade');
            $table->foreign('jadwal_id')->references('id')->on('jadwals')->onDelete('cascade');
            $table->primary(['unitkerja_id', 'jadwal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jadwal_unitkerja');
    }
}
