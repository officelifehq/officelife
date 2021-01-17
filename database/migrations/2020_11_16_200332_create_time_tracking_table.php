<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeTrackingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('timesheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->datetime('started_at');
            $table->datetime('ended_at');
            $table->string('status')->nullable();
            $table->datetime('approved_at')->nullable();
            $table->unsignedBigInteger('approver_id')->nullable();
            $table->string('approver_name')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('approver_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('time_tracking_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('timesheet_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('project_task_id')->nullable();
            $table->integer('duration');
            $table->datetime('happened_at');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->foreign('timesheet_id')->references('id')->on('timesheets')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('set null');
            $table->foreign('project_task_id')->references('id')->on('project_tasks')->onDelete('set null');
        });
    }
}
