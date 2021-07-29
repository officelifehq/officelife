<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecruitingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('recruiting_stage_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('name');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('recruiting_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recruiting_stage_template_id')->nullable();
            $table->string('name');
            $table->integer('position')->default(0);
            $table->timestamps();
            $table->foreign('recruiting_stage_template_id')->references('id')->on('recruiting_stage_templates')->onDelete('cascade');
        });
    }
}
