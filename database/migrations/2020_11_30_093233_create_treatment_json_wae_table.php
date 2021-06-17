<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentJsonWaeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_json_wae', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fk_treatment_json_id')->unsigned();
            $table->foreign('fk_treatment_json_id')->references('id')->on('treatment_json');
            $table->text('warning_datetime');
            $table->text('warning_code');
            $table->text('warning_value');
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
        Schema::dropIfExists('treatment_json_wae');
    }
}
