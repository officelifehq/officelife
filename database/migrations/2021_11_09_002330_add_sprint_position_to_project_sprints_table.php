<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSprintPositionToProjectSprintsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('project_sprints', function (Blueprint $table) {
            $table->integer('position')->after('project_board_id')->nullable();
        });

        Schema::create('project_sprint_employee_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('project_sprint_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->boolean('collapsed')->default(false);
            $table->foreign('project_sprint_id')->references('id')->on('project_sprints')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
}
