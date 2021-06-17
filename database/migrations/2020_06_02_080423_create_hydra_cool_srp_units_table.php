<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHydraCoolSrpUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hydra_cool_srp_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fk_hydracool_srp_id')->unsigned();
            $table->json('title');
            $table->integer('status')->default(1)->comment('0=in active, 1=active,2=deleted,3=suspended,4=released');
            $table->string('ip_address',50);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->foreign('fk_hydracool_srp_id')->references('id')->on('hydracool_srp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hydra_cool_srp_units');
    }
}
