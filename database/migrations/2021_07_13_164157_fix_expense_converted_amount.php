<?php

use App\Helpers\MoneyHelper;
use App\Models\Company\Expense;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixExpenseConvertedAmount extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        /** @var $connection \Illuminate\Database\Connection */
        $connection = DB::connection();
        if ($connection->getDriverName() === 'sqlite') {
            return;
        }

        Expense::whereNotNull('converted_amount')
            ->chunk(100, function ($expenses) {
                foreach ($expenses as $expense) {
                    $expense->converted_amount = MoneyHelper::parseInput((string) $expense->converted_amount, $expense->converted_to_currency);
                    $expense->save();
                }
            });

        Schema::table('expenses', function (Blueprint $table) {
            $table->unsignedBigInteger('amount')->change();
            $table->unsignedBigInteger('converted_amount')->change();
        });
    }
}
