<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUuiToFailedJob extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('failed_jobs', function (Blueprint $table) {
            $table->string('uuid')->unique()->after('id')->nullable();
        });
    }
}
