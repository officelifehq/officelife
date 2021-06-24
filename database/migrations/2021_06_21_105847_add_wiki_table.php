<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWikiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('wikis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('title');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wiki_id')->nullable();
            $table->string('title');
            $table->text('content');
            $table->integer('pageviews_counter');
            $table->timestamps();
            $table->foreign('wiki_id')->references('id')->on('wikis')->onDelete('cascade');
        });

        Schema::create('page_revisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('employee_name');
            $table->string('title');
            $table->text('content');
            $table->timestamps();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('pageviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('employee_name');
            $table->timestamps();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('set null');
        });

        Schema::create('team_wiki', function (Blueprint $table) {
            $table->unsignedBigInteger('wiki_id');
            $table->unsignedBigInteger('team_id');
            $table->timestamps();
            $table->foreign('wiki_id')->references('id')->on('wikis')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }
}
