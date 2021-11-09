<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSprintStoryOrderTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('project_sprints', function (Blueprint $table) {
            $table->boolean('is_board_backlog')->after('project_board_id')->default(false);
        });

        Schema::create('project_sprint_issue_order', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_sprint_id')->nullable();
            $table->unsignedBigInteger('project_issue_id')->nullable();
            $table->integer('order');
            $table->foreign('project_sprint_id')->references('id')->on('project_sprints')->onDelete('cascade');
            $table->foreign('project_issue_id')->references('id')->on('project_issues')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('project_sprint_issue_order');
    }
}
