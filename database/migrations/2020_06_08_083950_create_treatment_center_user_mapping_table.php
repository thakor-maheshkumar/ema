<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentCenterUserMappingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_center_user_mapping', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fk_treatment_center_id')->unsigned();
            $table->foreign('fk_treatment_center_id')->references('id')->on('treatment_center');
            
            $table->bigInteger('fk_user_id')->unsigned();
            $table->foreign('fk_user_id')->references('id')->on('users');
            
            $table->bigInteger('fk_role_id')->unsigned();
            $table->foreign('fk_role_id')->references('id')->on('roles');
            
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
        Schema::dropIfExists('treatment_center_user_mapping');
    }
}
