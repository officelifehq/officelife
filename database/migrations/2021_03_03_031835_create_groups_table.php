<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->text('mission')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('employee_group', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('group_id');
            $table->string('role')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });

        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id');
            $table->boolean('happened')->default(false);
            $table->datetime('happened_at')->nullable();
            $table->timestamps();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });

        Schema::create('agenda_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_id');
            $table->integer('position');
            $table->boolean('checked')->default(false);
            $table->string('summary');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('presented_by_id')->nullable();
            $table->timestamps();
            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
            $table->foreign('presented_by_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('meeting_decisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agenda_item_id');
            $table->text('description');
            $table->timestamps();
            $table->foreign('agenda_item_id')->references('id')->on('agenda_items')->onDelete('cascade');
        });

        Schema::create('employee_meeting', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('meeting_id');
            $table->boolean('attended')->default(false);
            $table->boolean('was_a_guest')->default(false);
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('meeting_id')->references('id')->on('meetings')->onDelete('cascade');
        });
    }
}
