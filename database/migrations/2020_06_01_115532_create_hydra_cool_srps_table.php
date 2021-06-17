<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHydraCoolSrpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hydracool_srp', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fk_treatment_centers_id')->unsigned();
            $table->bigInteger('serial_number')->comment('HydraCool SRP Serial Numbers');
            $table->integer('created_by');
            $table->integer('is_demo')->default(0)->comment('1=demo ,0=non_demo');
            $table->string('ip_address',50);
            $table->integer('status')->default(1)->comment('0=in active, 1=active,2=deleted,3=suspended,4=released');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->foreign('fk_treatment_centers_id')->references('id')->on('treatment_center');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hydracool_srp');
    }
}
