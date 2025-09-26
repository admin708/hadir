<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLibursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liburs', function (Blueprint $table) {
            $table->id();
            $table->date('bulan_tahun')->nullable();
            $table->string('hari_libur')->nullable()->comment('Selain hari Sabtu-Minggu');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liburs');
    }
}
