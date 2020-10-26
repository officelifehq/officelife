<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });

        Schema::create('employee_ship', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('ship_id');
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('ship_id')->references('id')->on('ships')->onDelete('cascade');
        });
    }
}
