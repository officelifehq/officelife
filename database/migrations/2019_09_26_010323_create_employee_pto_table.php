<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeePtoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_planned_holidays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->date('planned_date');
            $table->string('type');
            $table->boolean('full');
            $table->boolean('actually_taken')->default(false);
            $table->boolean('is_dummy')->default(false);
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::create('employee_daily_calendar_entries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->date('log_date');
            $table->double('new_balance');
            $table->double('daily_accrued_amount');
            $table->double('current_holidays_per_year');
            $table->double('default_amount_of_allowed_holidays_in_company');
            $table->boolean('on_holiday')->default(false);
            $table->boolean('sick_day')->default(false);
            $table->boolean('pto_day')->default(false);
            $table->boolean('remote')->default(false);
            $table->boolean('is_dummy')->default(false);
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
}
