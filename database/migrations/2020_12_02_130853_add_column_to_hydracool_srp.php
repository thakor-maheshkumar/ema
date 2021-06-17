<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToHydracoolSrp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hydracool_srp', function (Blueprint $table) {
            $table->string('manufacturer_name')->after('status');
            $table->date('manufacturing_date')->after('manufacturer_name');
            $table->date('sale_date')->after('manufacturing_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hydracool_srp', function (Blueprint $table) {
            //
        });
    }
}
