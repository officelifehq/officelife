<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->boolean('completed')->default(false);
            $table->string('title');
            $table->datetime('due_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->boolean('is_dummy')->default(false);
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('set null');
            $table->foreign('assignee_id')->references('id')->on('employees')->onDelete('set null');
        });
    }
}
