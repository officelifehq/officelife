<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRateYourManagerTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('rate_your_manager_surveys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->boolean('active')->default(true);
            $table->datetime('valid_until_at')->nullable();
            $table->timestamps();
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::create('rate_your_manager_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rate_your_manager_survey_id');
            $table->unsignedBigInteger('employee_id');
            $table->boolean('active')->default(true);
            $table->string('rating')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('reveal_identity_to_manager')->default(false);
            $table->timestamps();
            $table->foreign('rate_your_manager_survey_id')->references('id')->on('rate_your_manager_surveys')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
}
