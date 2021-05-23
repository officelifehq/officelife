<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyLogo extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedBigInteger('logo_file_id')->after('e_coffee_enabled')->nullable();
            $table->foreign('logo_file_id')->references('id')->on('files')->onDelete('set null');
        });
    }
}
