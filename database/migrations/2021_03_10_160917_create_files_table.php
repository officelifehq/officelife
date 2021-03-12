<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('uploader_employee_id')->nullable();
            $table->string('uploader_name')->nullable();
            $table->string('uuid');
            $table->string('original_url')->nullable();
            $table->string('cdn_url')->nullable();
            $table->string('mime_type');
            $table->string('name');
            $table->string('type');
            $table->integer('size');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('uploader_employee_id')->references('id')->on('employees')->onDelete('set null');
        });
    }
}
