<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name',200);
            $table->string('last_name',200);
            $table->string('company_name',200);
            $table->string('job_role',200);
            $table->integer('country_id');
            $table->string('email_address',100);
            $table->string('contact_telephone_number',15);
            $table->string('mobile_number',15);
            $table->text('message');
            $table->string('ip_address',50);
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
        Schema::dropIfExists('contact_data');
    }
}
