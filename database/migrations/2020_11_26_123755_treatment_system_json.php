<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TreatmentSystemJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_system_json', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('pid');
            $table->text('DSN');
            $table->text('TYP');
            $table->text('UTI');
            $table->text('USI');
            $table->text('WAE');

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
        Schema::dropIfExists('treatment_system_json');
    }
}
