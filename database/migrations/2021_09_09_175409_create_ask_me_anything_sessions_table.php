<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAskMeAnythingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('ask_me_anything_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->datetime('happened_at');
            $table->boolean('active')->default(false);
            $table->string('theme')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('ask_me_anything_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ask_me_anything_session_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('question');
            $table->boolean('answered')->default(false);
            $table->boolean('anonymous')->default(true);
            $table->timestamps();
            $table->foreign('ask_me_anything_session_id')->references('id')->on('ask_me_anything_sessions')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
}
