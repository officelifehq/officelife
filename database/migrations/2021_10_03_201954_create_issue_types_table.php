<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssueTypesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('issue_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('icon_hex_color');
            $table->timestamps();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });

        // populate default issue types for companies who are already on the instance
        $companies = DB::table('companies')->select('id')->get();
        foreach ($companies as $company) {
            $listOfIssues = config('officelife.issue_types');
            foreach ($listOfIssues as $name => $hexColor) {
                DB::table('issue_types')->insert([
                    'company_id' => $company->id,
                    'name' => trans('account.issue_type_' . $name),
                    'icon_hex_color' => $hexColor,
                    'created_at' => Carbon::now(),
                ]);
            }
        }
    }
}
