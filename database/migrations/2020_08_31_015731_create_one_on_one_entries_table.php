<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOneOnOneEntriesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('one_on_one_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manager_id');
            $table->unsignedBigInteger('employee_id');
            $table->boolean('happened')->default(false);
            $table->datetime('happened_at')->nullable();
            $table->timestamps();
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::create('one_on_one_talking_points', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('one_on_one_entry_id');
            $table->string('description');
            $table->boolean('checked')->default(false);
            $table->timestamps();
            $table->foreign('one_on_one_entry_id')->references('id')->on('one_on_one_entries')->onDelete('cascade');
        });

        Schema::create('one_on_one_action_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('one_on_one_entry_id');
            $table->string('description');
            $table->boolean('checked')->default(false);
            $table->timestamps();
            $table->foreign('one_on_one_entry_id')->references('id')->on('one_on_one_entries')->onDelete('cascade');
        });

        Schema::create('one_on_one_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('one_on_one_entry_id');
            $table->text('note');
            $table->timestamps();
            $table->foreign('one_on_one_entry_id')->references('id')->on('one_on_one_entries')->onDelete('cascade');
        });
    }
}
