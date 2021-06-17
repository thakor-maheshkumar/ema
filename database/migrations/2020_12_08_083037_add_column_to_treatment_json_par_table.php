<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTreatmentJsonParTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treatment_json_par', function (Blueprint $table) {
            $table->integer('mode_selected')->after('bottle')->nullable();
            $table->integer('vacuum')->after('mode_selected')->nullable();
            $table->text('intensity_of_vacuum')->nullable()->change();
            $table->text('pulsed')->after('vacuum')->nullable();
            $table->text('flow')->nullable()->change();
            $table->text('bottle')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('treatment_json_par', function (Blueprint $table) {
            //
        });
    }
}
