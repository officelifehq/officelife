<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('licence_key')->after('code_to_join_company')->nullable();
            $table->datetime('valid_until_at')->after('licence_key')->nullable();
            $table->string('purchaser_email')->after('valid_until_at')->nullable();
            $table->string('frequency')->after('purchaser_email')->nullable();
            $table->string('quantity')->after('purchaser_email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('licence_keys');
    }
};
