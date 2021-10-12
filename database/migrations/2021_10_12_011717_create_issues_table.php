<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('project_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('reporter_id')->nullable();
            $table->unsignedBigInteger('issue_type_id')->nullable();
            $table->string('key');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('reporter_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('issue_type_id')->references('id')->on('issue_types')->onDelete('set null');
        });
    }
}
