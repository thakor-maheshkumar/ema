<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupportDocumentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_document_files', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('fk_support_document_id')->unsigned();
            $table->foreign('fk_support_document_id')->references('id')->on('support_documents');
            $table->text('document_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_document_files');
    }
}
