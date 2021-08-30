<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFulfilledAtDateToJobOpeningTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('job_openings', function (Blueprint $table) {
            $table->datetime('fulfilled_at')->nullable()->after('activated_at');
        });
    }
}
