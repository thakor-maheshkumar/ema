<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnFromTreatmentBootJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treatment_boot_json', function (Blueprint $table) {
            $table->dropColumn('pid');
            $table->dropColumn('UID');
            $table->dropColumn('SKT');
            $table->dropColumn('SKC');
            $table->dropColumn('TEC');
            $table->dropColumn('PAR');
            $table->dropColumn('UTF');
            $table->dropColumn('USF');
            $table->dropColumn('MOD');
            $table->dropColumn('COP');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('treatment_boot_json', function (Blueprint $table) {
            $table->string('pid');
            $table->string('UID');
            $table->string('SKT');
            $table->string('SKC');
            $table->string('TEC');
            $table->string('PAR');
            $table->string('UTF');
            $table->string('USF');
            $table->string('MOD');
            $table->string('COP');
        });
    }
}
