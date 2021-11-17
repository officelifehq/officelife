<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisciplineCasesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('discipline_cases', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('opened_by_employee_id')->nullable();
            $table->string('opened_by_employee_name')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('opened_by_employee_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('discipline_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('discipline_case_id');
            $table->unsignedBigInteger('author_id')->nullable();
            $table->string('author_name')->nullable();
            $table->datetime('happened_at');
            $table->text('description');
            $table->timestamps();
            $table->foreign('discipline_case_id')->references('id')->on('discipline_cases')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('discipline_event_file', function (Blueprint $table) {
            $table->unsignedBigInteger('file_id');
            $table->unsignedBigInteger('discipline_event_id');
            $table->timestamps();
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->foreign('discipline_event_id')->references('id')->on('discipline_events')->onDelete('cascade');
        });
    }
}
