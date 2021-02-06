<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateECoffeeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('e_coffees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->integer('batch_number');
            $table->boolean('active')->default(false);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('e_coffee_matches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('e_coffee_id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('with_employee_id');
            $table->boolean('happened')->default(false);
            $table->timestamps();
            $table->foreign('e_coffee_id')->references('id')->on('e_coffees')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('with_employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('e_coffee_enabled')->default(false)->after('currency')->nullable();
        });
    }
}
