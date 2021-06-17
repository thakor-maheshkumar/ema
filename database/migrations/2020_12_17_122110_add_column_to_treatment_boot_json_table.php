<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTreatmentBootJsonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treatment_boot_json', function (Blueprint $table) {
            $table->string('status')->after('FLB')->default('Action Pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('treatment_boot_json', function (Blueprint $table) {
            $table->text('status')->after('FLB');
        });
    }
}
