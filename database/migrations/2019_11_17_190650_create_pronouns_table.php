<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePronounsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // necessary for SQLlite
        Schema::enableForeignKeyConstraints();

        Schema::create('pronouns', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('translation_key');
            $table->timestamps();
        });

        DB::table('pronouns')->insert([
            'label' => 'he/him',
            'translation_key' => 'account.pronoun_he_him',
        ]);
        DB::table('pronouns')->insert([
            'label' => 'she/her',
            'translation_key' => 'account.pronoun_she_her',
        ]);
        DB::table('pronouns')->insert([
            'label' => 'they/them',
            'translation_key' => 'account.pronoun_they_them',
        ]);
        DB::table('pronouns')->insert([
            'label' => 'per/per',
            'translation_key' => 'account.pronoun_per_per',
        ]);
        DB::table('pronouns')->insert([
            'label' => 've/ver',
            'translation_key' => 'account.pronoun_ve_ver',
        ]);
        DB::table('pronouns')->insert([
            'label' => 'xe/xem',
            'translation_key' => 'account.pronoun_xe_xem',
        ]);
        DB::table('pronouns')->insert([
            'label' => 'ze/hir',
            'translation_key' => 'account.pronoun_ze_hir',
        ]);

        Schema::table('employees', function (Blueprint $table) {
            $table->unsignedBigInteger('pronoun_id')->after('last_name')->nullable();
            $table->foreign('pronoun_id')->references('id')->on('pronouns')->onDelete('set null');
        });
    }
}
