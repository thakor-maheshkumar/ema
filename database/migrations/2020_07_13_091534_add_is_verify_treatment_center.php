<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsVerifyTreatmentCenter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treatment_center', function (Blueprint $table) {
            $table->integer('is_verified')->default(0);
            $table->timestamp('verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('treatment_center', function (Blueprint $table) {
             $table->dropColumn('is_verified');
            $table->dropColumn('verified_at');
        });
    }
}
