<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvatarsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('employees', function (Blueprint $table) {
            $table->string('avatar_original_filename')->after('avatar')->nullable();
            $table->string('avatar_extension')->after('avatar_original_filename')->nullable();
            $table->bigInteger('avatar_size')->after('avatar_extension')->nullable();
            $table->integer('avatar_height')->after('avatar_size')->nullable();
            $table->integer('avatar_width')->after('avatar_height')->nullable();
        });
    }
}
