<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Models\Company\ExpenseCategory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminExpenseViewHelper;

class AdminExpenseViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_information_about_expense_categories_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $category = factory(ExpenseCategory::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $response = AdminExpenseViewHelper::categories($michael->company);

        $this->assertCount(
            1,
            $response
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/account/expenses/'.$category->id,
                ],
            ],
            $response->toArray()
        );
    }
}
