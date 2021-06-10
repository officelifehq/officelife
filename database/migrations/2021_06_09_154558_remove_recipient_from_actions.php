<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRecipientFromActions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::drop('actions');
        Schema::drop('steps');
        Schema::drop('flows');

        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('type');
            $table->string('trigger')->nullable();
            $table->boolean('anniversary')->default(false);
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
            $table->json('content');
            $table->timestamps();
            $table->foreign('step_id')->references('id')->on('steps')->onDelete('cascade');
        });

        Schema::create('scheduled_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->unsignedBigInteger('employee_id');
            $table->datetime('triggered_at');
            $table->json('content');
            $table->boolean('processed')->default(false);
            $table->timestamps();
            $table->foreign('action_id')->references('id')->on('actions')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });
    }
}
