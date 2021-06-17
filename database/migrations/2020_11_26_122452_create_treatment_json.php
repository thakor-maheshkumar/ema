<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_json', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('pid');
            $table->text('MOD');
            $table->text('TYP');
            $table->text('SKC');
            $table->text('UID');
            $table->integer('UID_flag');
            $table->text('unique_UID_value');
            $table->text('TEC');
            $table->text('SKT');
            $table->text('STA');
            $table->text('DSN');
            $table->dateTime('USF');
            $table->dateTime('UTF');
            $table->dateTime('UTI');
            $table->dateTime('USI');


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
        Schema::dropIfExists('treatment_json');
    }
}
