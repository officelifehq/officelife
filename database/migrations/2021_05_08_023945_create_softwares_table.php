<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSoftwaresTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('softwares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('website')->nullable();
            $table->text('product_key')->nullable();
            $table->integer('seats');
            $table->string('licensed_to_name')->nullable();
            $table->string('licensed_to_email_address')->nullable();
            $table->string('order_number')->nullable();
            $table->integer('purchase_amount')->nullable();
            $table->string('currency')->nullable();
            $table->integer('converted_purchase_amount')->nullable();
            $table->string('converted_to_currency')->nullable();
            $table->datetime('converted_at')->nullable();
            $table->double('exchange_rate')->nullable();
            $table->datetime('purchased_at')->nullable();
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('employee_software', function (Blueprint $table) {
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('software_id');
            $table->text('product_key')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('software_id')->references('id')->on('softwares')->onDelete('cascade');
        });
    }
}
