<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('employee_statuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->boolean('is_dummy')->default(false);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_status_id')->after('position_id')->nullable();
            $table->foreign('employee_status_id')->references('id')->on('employee_statuses')->onDelete('set null');
        });
    }
}
