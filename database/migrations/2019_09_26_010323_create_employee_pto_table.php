<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeePtoTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('employee_planned_holidays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->datetime('planned_date');
            $table->string('type');
            $table->boolean('full');
            $table->boolean('actually_taken')->default(false);
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::create('employee_daily_calendar_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->datetime('log_date');
            $table->double('new_balance');
            $table->double('daily_accrued_amount');
            $table->double('current_holidays_per_year');
            $table->double('default_amount_of_allowed_holidays_in_company');
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
}
