<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Models\Company\Employee;
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

    /** @test */
    public function it_gets_information_about_employees_with_the_right_to_manage_expenses_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
            'can_manage_expenses' => true,
            'first_name' => 'dwight',
            'last_name' => 'schrute',
        ]);
        factory(Employee::class, 2)->create([
            'company_id' => $michael->company_id,
            'can_manage_expenses' => false,
        ]);

        $response = AdminExpenseViewHelper::employees($michael->company);

        $this->assertCount(
            1,
            $response
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => 'dwight schrute',
                    'avatar' => $dwight->avatar,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$dwight->id,
                ],
            ],
            $response->toArray()
        );
    }
}
