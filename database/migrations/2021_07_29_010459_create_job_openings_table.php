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
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('position_id');
            $table->unsignedBigInteger('recruiting_stage_template_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->boolean('active')->default(false);
            $table->boolean('fulfilled')->default(false);
            $table->string('reference_number')->nullable();
            $table->string('title');
            $table->string('slug');
            $table->text('description');
            $table->datetime('activated_at')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
            $table->foreign('recruiting_stage_template_id')->references('id')->on('recruiting_stage_templates')->onDelete('set null');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
        });

        Schema::create('job_opening_sponsor', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('job_opening_id');
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('job_opening_id')->references('id')->on('job_openings')->onDelete('cascade');
        });

        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('job_opening_id');
            $table->string('name');
            $table->string('email');
            $table->string('url', 500)->nullable();
            $table->unsignedBigInteger('desired_salary')->nullable();
            $table->text('notes')->nullable();
            $table->uuid('uuid');
            $table->timestamps();
            $table->foreign('job_opening_id')->references('id')->on('job_openings')->onDelete('cascade');
        });
    }
}
