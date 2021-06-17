<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentJsonCsnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_json_csn', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fk_treatment_json_id')->unsigned();
            $table->foreign('fk_treatment_json_id')->references('id')->on('treatment_json');
            $table->text('bottle_id');
            $table->text('initial_value');
            $table->text('start_value');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treatment_json_csn');
    }
}
