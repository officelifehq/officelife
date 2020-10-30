<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyCalendarTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('company_calendars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_pto_policy_id');
            $table->datetime('day');
            $table->integer('day_of_week');
            $table->integer('day_of_year');
            $table->boolean('is_worked')->default(true);
            $table->timestamps();
            $table->foreign('company_pto_policy_id')->references('id')->on('company_pto_policies')->onDelete('cascade');
        });
    }
}
