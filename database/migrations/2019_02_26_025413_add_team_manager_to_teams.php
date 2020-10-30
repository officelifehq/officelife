<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamManagerToTeams extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('teams', function (Blueprint $table) {
            $table->unsignedBigInteger('team_leader_id')->after('company_id')->nullable();
            $table->foreign('team_leader_id')->references('id')->on('employees')->onDelete('set null');
        });
    }
}
