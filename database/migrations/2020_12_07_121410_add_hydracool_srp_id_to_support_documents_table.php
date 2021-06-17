<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHydracoolSrpIdToSupportDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_documents', function (Blueprint $table) {
            $table->bigInteger('fk_hydracool_srp_id')->unsigned();
            $table->foreign('fk_hydracool_srp_id')->references('id')->on('hydracool_srp')->after('category_id')->nullable();
            $table->dropColumn('device_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support_documents', function (Blueprint $table) {
            //
        });
    }
}
