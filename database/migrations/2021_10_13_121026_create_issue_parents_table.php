<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueParentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('project_issue_parents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_project_issue_id');
            $table->unsignedBigInteger('child_project_issue_id');
            $table->foreign('parent_project_issue_id')->references('id')->on('project_issues')->onDelete('cascade');
            $table->foreign('child_project_issue_id')->references('id')->on('project_issues')->onDelete('cascade');
        });
    }
}
