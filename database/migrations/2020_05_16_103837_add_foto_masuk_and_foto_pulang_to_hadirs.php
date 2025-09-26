<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFotoMasukAndFotoPulangToHadirs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hadirs', function (Blueprint $table) {
            $table->string('foto_masuk')->nullable()->after('confirmed');
            $table->string('foto_pulang')->nullable()->after('foto_masuk');
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
            $table->dropColumn(['foto_masuk', 'foto_pulang']);
        });
    }
}
