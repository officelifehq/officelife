<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoryPointTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('project_issues', function (Blueprint $table) {
            $table->integer('story_points')->after('description')->nullable();
        });

        Schema::create('project_issue_story_points_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_issue_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->integer('story_points')->nullable();
            $table->timestamps();
            $table->foreign('project_issue_id')->references('id')->on('project_issues')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
        });
    }
}
