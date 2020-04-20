<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyPtoPolicies extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('company_pto_policies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->integer('year');
            $table->integer('total_worked_days');
            $table->integer('default_amount_of_allowed_holidays')->nullable();
            $table->integer('default_amount_of_sick_days')->nullable();
            $table->integer('default_amount_of_pto_days')->nullable();
            $table->boolean('is_dummy')->default(false);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }
}
