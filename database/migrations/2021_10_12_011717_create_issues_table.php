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
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('project_issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('project_board_id')->nullable();
            $table->unsignedBigInteger('reporter_id')->nullable();
            $table->unsignedBigInteger('issue_type_id')->nullable();
            $table->integer('id_in_project');
            $table->string('key');
            $table->string('title');
            $table->string('slug');
            $table->mediumText('description')->nullable();
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('reporter_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('issue_type_id')->references('id')->on('issue_types')->onDelete('set null');
            $table->foreign('project_board_id')->references('id')->on('project_boards')->onDelete('set null');
        });

        Schema::create('project_issue_project_sprint', function (Blueprint $table) {
            $table->unsignedBigInteger('project_issue_id')->nullable();
            $table->unsignedBigInteger('project_sprint_id')->nullable();
            $table->foreign('project_issue_id')->references('id')->on('project_issues')->onDelete('cascade');
            $table->foreign('project_sprint_id')->references('id')->on('project_sprints')->onDelete('cascade');
        });

        Schema::create('project_labels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('name');
            $table->timestamps();
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });

        Schema::create('project_issue_project_label', function (Blueprint $table) {
            $table->unsignedBigInteger('project_issue_id')->nullable();
            $table->unsignedBigInteger('project_label_id')->nullable();
            $table->foreign('project_issue_id')->references('id')->on('project_issues')->onDelete('cascade');
            $table->foreign('project_label_id')->references('id')->on('project_labels')->onDelete('cascade');
        });
    }
}
