<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPulangCepatAndCatatanToPresensis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('presensis', function (Blueprint $table) {
            $table->boolean('pulang_cepat')->nullable()->default(false)->after('confirmed');
            $table->text('catatan')->nullable()->after('pulang_cepat');
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
            $table->dropColumn(['pulang_cepat', 'catatan']);
        });
    }
}
