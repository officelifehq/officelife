<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserTokenSocialite extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('user_tokens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('driver_id');
            $table->string('driver', 50);
            $table->string('format', 5);
            $table->string('token', 250);
            $table->string('token_secret', 1024)->nullable();
            $table->string('refresh_token', 1024)->nullable();
            $table->datetime('expires_in')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['driver', 'driver_id']);
        });
    }
}
