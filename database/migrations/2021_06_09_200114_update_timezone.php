<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTimezone extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('timezone')->after('password')->default('UTC');
        });

        DB::table('employees')->whereNotNull('user_id')
            ->chunkById(200, function ($employees) {
                foreach ($employees as $employee) {
                    DB::table('users')
                        ->where('id', $employee->user_id)
                        ->update(['timezone' => $employee->timezone]);
                }
            });

        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('timezone');
        });
    }
}
