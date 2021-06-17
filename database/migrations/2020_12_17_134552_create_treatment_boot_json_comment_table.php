<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentBootJsonCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_boot_json_comment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('boot_json_id')->unsigned();
            $table->text('comment');
            $table->timestamps();
            $table->foreign('boot_json_id')->references('id')->on('treatment_boot_json');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('treatment_boot_json_comment');
    }
}
