<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmployeesWithAvatarTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('avatar_file_id')->after('phone_number')->nullable();
            $table->foreign('avatar_file_id')->references('id')->on('files')->onDelete('set null');
        });
    }
}
