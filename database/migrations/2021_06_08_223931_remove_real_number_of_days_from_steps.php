<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveRealNumberOfDaysFromSteps extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('steps', function (Blueprint $table) {
            $table->dropColumn('real_number_of_days');
        });
    }
}
