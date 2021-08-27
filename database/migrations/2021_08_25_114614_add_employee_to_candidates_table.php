<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeToCandidatesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('candidates', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id')->nullable()->after('job_opening_id');
            $table->string('employee_name')->nullable()->after('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
        });
    }
}
