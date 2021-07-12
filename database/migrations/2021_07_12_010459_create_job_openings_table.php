<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobOpeningsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('sponsored_by_employee_id')->nullable();
            $table->boolean('active')->default(false);
            $table->boolean('fulfilled')->default(false);
            $table->string('reference_number')->nullable();
            $table->string('title');
            $table->text('description');
            $table->datetime('activated_at')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->foreign('sponsored_by_employee_id')->references('id')->on('employees')->onDelete('set null');
        });
    }
}
