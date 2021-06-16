<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTwoFactorColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('two_factor_secret')
                    ->after('password')
                    ->nullable();

            $table->text('two_factor_recovery_codes')
                    ->after('two_factor_secret')
                    ->nullable();
        });
    }
}
