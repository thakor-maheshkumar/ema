<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInternalIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
             $table->string('internal_id',5)->after('id');
             $table->string('primary_telephone_number',20)->after('password');
             $table->string('mobile_telephone_number',15)->after('primary_telephone_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn('internal_id');
             $table->dropColumn('primary_telephone_number');
             $table->dropColumn('mobile_telephone_number');
        });
    }
}
