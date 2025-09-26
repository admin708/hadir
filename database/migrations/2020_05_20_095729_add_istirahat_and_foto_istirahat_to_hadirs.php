<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIstirahatAndFotoIstirahatToHadirs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hadirs', function (Blueprint $table) {
            $table->string('istirahat')->nullable()->after('masuk');
            $table->string('foto_istirahat')->nullable()->after('foto_masuk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hadirs', function (Blueprint $table) {
            $table->dropColumn(['istirahat', 'foto_istirahat']);
        });
    }
}
