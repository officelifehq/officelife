<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamUsefulLinksTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('team_useful_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('team_id');
            $table->string('type');
            $table->string('label')->nullable();
            $table->string('url');
            $table->timestamps();
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }
}
