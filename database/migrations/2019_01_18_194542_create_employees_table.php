<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('company_id');
            $table->string('email')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->boolean('locked')->default(false);
            $table->datetime('hired_at')->nullable();
            $table->uuid('uuid');
            $table->string('avatar')->nullable();
            $table->string('twitter_handle')->nullable();
            $table->string('slack_handle')->nullable();
            $table->integer('permission_level');
            $table->string('invitation_link')->nullable();
            $table->timestamp('invitation_used_at')->nullable();
            $table->integer('consecutive_worklog_missed')->default(0);
            $table->double('amount_of_allowed_holidays')->nullable();
            $table->double('holiday_balance')->nullable();
            $table->string('default_dashboard_view')->default('me');
            $table->boolean('can_manage_expenses')->default(false);
            $table->boolean('display_welcome_message')->default(false);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->index('invitation_link');
            $table->index('first_name');
            $table->index('last_name');
        });
    }
}
