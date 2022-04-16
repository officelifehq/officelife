<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderToTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('project_issue_project_sprint', function (Blueprint $table) {
            $table->integer('position')->nullable()->after('project_sprint_id');
        });

        Schema::drop('project_sprint_issue_order');
    }
}
