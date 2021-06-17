<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('internal_id',5);
            $table->string('full_company_name',200);
            $table->string('abbreviated_company_name',200);
            $table->string('group_name',200);
            $table->text('full_address');
            $table->string('building_name',200);
            $table->text('address1');
            $table->text('address2');
            $table->text('address3');
            $table->string('state',200);
            $table->integer('zipcode');
            $table->string('position',200);            
            $table->integer('country_id');
            $table->string('telephone_number',15);
            $table->string('fax_number',15);
            $table->string('web_site',100);
            $table->string('name_of_primary_contact',100);
            $table->string('telephone_number_of_primary_contact',15);
            $table->string('mobile_number_of_primary_contact',15);
            $table->string('email_of_primary_contact',100);
            $table->string('distributor_code')->nullable();
            $table->integer('created_by');
            $table->string('ip_address',50);
            $table->integer('status')->default(1)->comment('0=inactive, 1 = Active/Release, 2=Deleted, 3=Suspended');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
        Schema::create('distributor_user_mapping', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fk_distributor_id')->unsigned();
            $table->foreign('fk_distributor_id')->references('id')->on('distributors')->onDelete('cascade');
            $table->bigInteger('fk_user_id')->unsigned();
            $table->foreign('fk_user_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('fk_role_id')->unsigned();
            $table->foreign('fk_role_id')->references('id')->on('roles')->onDelete('cascade');
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
        Schema::dropIfExists('distributors');
        Schema::dropIfExists('distributor_user_mapping');
    }
}
