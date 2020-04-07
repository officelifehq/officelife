<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDescriptionToTeams extends Migration
{
    /**
     * Run the migrations.
     *
     */
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->text('description')->after('name')->nullable();
        });
    }
}
