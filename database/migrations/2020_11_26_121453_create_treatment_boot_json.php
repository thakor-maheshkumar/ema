<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentBootJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_boot_json', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('pid');
            $table->text('DSN');
            $table->text('TYP');
            $table->text('UTI');
            $table->text('USI');
            $table->text('UID');
            $table->text('STA');
            $table->text('SKT');
            $table->text('SKC');
            $table->text('TEC');
            $table->text('CSN');
            $table->text('PAR');
            $table->text('WAE');
            $table->text('UTF');
            $table->text('USF');
            $table->text('MOD');
            $table->text('COP');
            $table->text('MIB');
            $table->text('ULB');
            $table->text('MAB');
            $table->text('VIB');
            $table->text('RSS');
            $table->text('UST');
            $table->text('GSB');
            $table->text('COB');
            $table->text('DFW');
            $table->text('FLB');

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
        Schema::dropIfExists('treatment_boot_json');
    }
}
