<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameNameColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_documents', function (Blueprint $table) {
            $table->renameColumn('name', 'device_name');
            $table->string('primary_email',255)->after('id');
            $table->bigInteger('category_id')->after('primary_email');
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
