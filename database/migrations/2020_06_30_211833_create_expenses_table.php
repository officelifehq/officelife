<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('expense_category_id')->nullable();
            $table->string('status');
            $table->string('title');
            $table->integer('amount');
            $table->string('currency');
            $table->text('description')->nullable();
            $table->date('expensed_at');
            $table->unsignedBigInteger('manager_approver_id')->nullable();
            $table->string('manager_approver_name')->nullable();
            $table->date('manager_approver_approved_at')->nullable();
            $table->unsignedBigInteger('accounting_approver_id')->nullable();
            $table->string('accounting_approver_name')->nullable();
            $table->date('accounting_approver_approved_at')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('expense_category_id')->references('id')->on('expense_categories')->onDelete('set null');
            $table->foreign('manager_approver_id')->references('id')->on('employees')->onDelete('set null');
            $table->foreign('accounting_approver_id')->references('id')->on('employees')->onDelete('set null');
        });
    }
}
