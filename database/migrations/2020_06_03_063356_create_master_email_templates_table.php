<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterEmailTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_email_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('template_name',150);
            $table->text('template_content');
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
        Schema::dropIfExists('master_email_templates');
    }
}
