<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportJobTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('import_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('author_name')->nullable();
            $table->string('status');
            $table->datetime('import_started_at')->nullable();
            $table->datetime('import_ended_at')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('import_job_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_job_id');
            $table->string('employee_first_name')->nullable();
            $table->string('employee_last_name')->nullable();
            $table->string('employee_email')->nullable();
            $table->boolean('skipped_during_upload')->default(false);
            $table->string('skipped_during_upload_reason')->nullable();
            $table->boolean('skipped_during_import')->default(false);
            $table->string('skipped_during_import_reason')->nullable();
            $table->timestamps();
            $table->foreign('import_job_id')->references('id')->on('import_jobs')->onDelete('cascade');
        });
    }
}
