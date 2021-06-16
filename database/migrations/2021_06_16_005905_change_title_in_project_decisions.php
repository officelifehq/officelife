<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTitleInProjectDecisions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('project_decisions', function (Blueprint $table) {
            $table->text('title')->change();
        });
    }
}
