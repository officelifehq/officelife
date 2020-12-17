<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractRenewalDateToEmployees extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->datetime('contract_renewed_at')->nullable()->after('hired_at');
        });
    }
}
