<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToTreatmentBootJsonComment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('treatment_boot_json_comment', function (Blueprint $table) {
            $table->bigInteger('user_id')->unsigned()->after('comment');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('treatment_boot_json_comment', function (Blueprint $table) {
            //
        });
    }
}
