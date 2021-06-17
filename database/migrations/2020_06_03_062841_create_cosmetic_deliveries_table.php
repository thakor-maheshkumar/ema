<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCosmeticDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cosmetic_deliveries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fk_treatment_centers_id');
            $table->json('solution_bottel_pack')->nullable();
            $table->json('solution_1')->nullable();
            $table->json('solution_2')->nullable();
            $table->json('solution_3')->nullable();
            $table->json('solution_4')->nullable();
            $table->json('cosmetic_fresh_pack')->nullable();
            $table->json('cosmetic_bright_pack')->nullable();
            $table->json('booster_packs')->nullable();
            $table->json('aquaB_tips')->nullable();
            $table->date('delivery_date')->nullable();
            $table->integer('created_by');
            $table->string('ip_address',50);
            $table->integer('status')->default(1)->comment('0=inactive, 1 = Active/Release, 2=Deleted, 3=Suspended');
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
        Schema::dropIfExists('cosmetic_deliveries');
    }
}
