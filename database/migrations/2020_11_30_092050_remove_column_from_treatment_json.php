<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveColumnFromTreatmentJson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treatment_json', function (Blueprint $table) {
            $table->dropColumn('pid');
            $table->dropColumn('WAE');
            $table->dropColumn('PAR');
            $table->dropColumn('CSN');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('treatment_json', function (Blueprint $table) {
            $table->string('pid');
        });
    }
}
