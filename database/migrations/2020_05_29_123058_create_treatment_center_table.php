<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentCenterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_center', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('internal_id',5);
            $table->string('full_company_name',200);
            $table->string('abbreviated_company_name',200);
            $table->string('group_name',200);
            $table->text('full_address');
            $table->string('building_name',200);
            $table->text('address_1');
            $table->text('address_2');
            $table->text('address_3');
            $table->string('state',200);
            $table->integer('zipcode');
            $table->string('position',200);            
            $table->integer('country_id');
            $table->string('country_code',50);
            $table->string('fax_number',15);
            $table->string('web_site',100);
            $table->string('name_of_primary_contact',200);
            $table->string('telephone_number_of_primary_contact',15);
            $table->string('mobile_number_of_primary_contact',15);
            $table->string('email_of_primary_contact',100);
            $table->string('distributors','255')->nullable();
            $table->string('treatment_ema_code',100);
            $table->integer('is_ema')->default(1)->comment('1=ema, 0 = non_ema');
            $table->integer('created_by');
            $table->string('ip_address',50);
            $table->integer('status')->default(1)->comment('0=inactive, 1 = Active/Release, 2=Deleted, 3=Suspended');
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
        Schema::dropIfExists('treatment_center');
    }
}
