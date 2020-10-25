<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuessEmployeesGamesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('guess_employee_games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_who_played_id');
            $table->unsignedBigInteger('employee_to_find_id');
            $table->unsignedBigInteger('first_other_employee_to_find_id');
            $table->unsignedBigInteger('second_other_employee_to_find_id');
            $table->boolean('played')->default(false);
            $table->boolean('found')->default(false);
            $table->timestamps();
            $table->foreign('employee_who_played_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('employee_to_find_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('first_other_employee_to_find_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('second_other_employee_to_find_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
}
