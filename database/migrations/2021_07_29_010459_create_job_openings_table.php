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
            $table->string('title');
            $table->boolean('active')->default(false);
            $table->boolean('fulfilled')->default(false);
            $table->string('reference_number')->nullable();
            $table->string('slug');
            $table->text('description');
            $table->unsignedBigInteger('page_views')->default(0);
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
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('job_opening_id');
            $table->string('name');
            $table->string('email');
            $table->string('url', 500)->nullable();
            $table->string('desired_salary')->nullable();
            $table->text('notes')->nullable();
            $table->string('uuid');
            $table->boolean('application_completed')->default(false);
            $table->boolean('rejected')->default(false);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('job_opening_id')->references('id')->on('job_openings')->onDelete('cascade');
        });

        Schema::table('job_openings', function (Blueprint $table) {
            $table->unsignedBigInteger('fulfilled_by_candidate_id')->after('team_id')->nullable();
            $table->foreign('fulfilled_by_candidate_id')->references('id')->on('candidates')->onDelete('set null');
        });

        Schema::create('candidate_file', function (Blueprint $table) {
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('candidate_id');
            $table->timestamps();
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
        });

        Schema::create('candidate_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_id');
            $table->unsignedBigInteger('decider_id')->nullable();
            $table->string('stage_name');
            $table->integer('stage_position');
            $table->string('status');
            $table->string('decider_name')->nullable();
            $table->datetime('decided_at')->nullable();
            $table->timestamps();
            $table->foreign('candidate_id')->references('id')->on('candidates')->onDelete('cascade');
            $table->foreign('decider_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('candidate_stage_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_stage_id');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->text('note');
            $table->string('author_name')->nullable();
            $table->timestamps();
            $table->foreign('candidate_stage_id')->references('id')->on('candidate_stages')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('candidate_stage_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('candidate_stage_id');
            $table->unsignedBigInteger('participant_id')->nullable();
            $table->string('participant_name')->nullable();
            $table->boolean('participated')->default(false);
            $table->datetime('participated_at')->nullable();
            $table->timestamps();
            $table->foreign('candidate_stage_id')->references('id')->on('candidate_stages')->onDelete('cascade');
            $table->foreign('participant_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('candidate_stage_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->unsignedBigInteger('job_opening_id');
            $table->string('author_name')->nullable();
            $table->string('assignee_name')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->boolean('checked')->default(false);
            $table->timestamps();
            $table->foreign('author_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('assignee_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('job_opening_id')->references('id')->on('job_openings')->onDelete('cascade');
        });
    }
}
