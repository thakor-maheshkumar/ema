<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',150);
            $table->string('document_url',255);
            $table->integer('is_public')->default(0);
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
        Schema::dropIfExists('support_documents');
    }
}
