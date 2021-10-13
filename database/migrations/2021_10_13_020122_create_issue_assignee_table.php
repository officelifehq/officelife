<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueAssigneeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('project_issue_assignees', function (Blueprint $table) {
            $table->unsignedBigInteger('project_issue_id');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('project_issue_id')->references('id')->on('project_issues')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
}
