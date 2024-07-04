<?php

use App\Models\Company\Company;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AddCompanySlugToCompanies extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::table('companies', function (Blueprint $table) {
            $table->string('slug')->after('name')->nullable();
        });

        Company::chunk(200, function ($companies) {
            foreach ($companies as $company) {
                $company->slug = Str::slug($company->name, '-');
                $company->save();
            }
        });
    }
}
