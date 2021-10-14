<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoryPointTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('project_issues', function (Blueprint $table) {
            $table->integer('story_points')->after('description');
        });
    }
}
