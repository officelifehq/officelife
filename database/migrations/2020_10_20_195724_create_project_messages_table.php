<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectMessagesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('project_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('title');
            $table->text('content');
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('project_message_read_status', function (Blueprint $table) {
            $table->unsignedBigInteger('project_message_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();
            $table->foreign('project_message_id')->references('id')->on('project_messages')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
}
