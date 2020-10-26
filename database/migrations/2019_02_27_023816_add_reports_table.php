<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('direct_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('manager_id');
            $table->unsignedBigInteger('employee_id');
            $table->timestamps();
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }
}
