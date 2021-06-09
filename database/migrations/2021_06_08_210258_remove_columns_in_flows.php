<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnsInFlows extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('actions', function (Blueprint $table) {
            $table->dropColumn('recipient');
        });
    }
}
