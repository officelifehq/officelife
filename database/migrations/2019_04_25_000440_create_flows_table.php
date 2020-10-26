<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('type');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flow_id');
            $table->integer('number')->nullable();
            $table->string('unit_of_time')->nullable();
            $table->string('modifier')->default('same_day');
            $table->double('real_number_of_days')->default(0);
            $table->timestamps();
            $table->foreign('flow_id')->references('id')->on('flows')->onDelete('cascade');
        });

        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('step_id');
            $table->string('type');
            $table->string('recipient');
            $table->text('specific_recipient_information')->nullable();
            $table->timestamps();
            $table->foreign('step_id')->references('id')->on('steps')->onDelete('cascade');
        });
    }
}
