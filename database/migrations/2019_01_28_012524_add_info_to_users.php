<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('account_id')->nullable();
            $table->string('last_name')->after('first_name')->nullable();
            $table->string('middle_name')->after('last_name')->nullable();
            $table->string('nickname')->after('middle_name')->nullable();
        });
    }
}
