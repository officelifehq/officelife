<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateFlowsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->json('content')->after('type');
        });

        Schema::table('steps', function (Blueprint $table) {
            $table->double('relative_number_of_days')->after('modifier');
        });
    }
}
