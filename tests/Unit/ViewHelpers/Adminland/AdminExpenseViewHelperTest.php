<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Helpers\ImageHelper;
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
        $category = ExpenseCategory::factory()->create([
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
                ],
            ],
            $response->toArray()
        );
    }

    /** @test */
    public function it_gets_information_about_employees_with_the_right_to_manage_expenses_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = Employee::factory()->create([
            'company_id' => $michael->company_id,
            'can_manage_expenses' => true,
            'first_name' => 'dwight',
            'last_name' => 'schrute',
        ]);
        Employee::factory(2)->create([
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
                    'avatar' => ImageHelper::getAvatar($dwight, 23),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$dwight->id,
                ],
            ],
            $response->toArray()
        );
    }

    /** @test */
    public function it_searches_employees(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
            'can_manage_expenses' => false,
        ]);
        $dwight = Employee::factory()->create([
            'first_name' => 'alb',
            'last_name' => 'bli',
            'email' => 'alb@bli',
            'company_id' => $michael->company_id,
            'can_manage_expenses' => true,
        ]);
        // the following should not be included in the search results
        Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
            'locked' => true,
            'company_id' => $michael->company_id,
        ]);

        $collection = AdminExpenseViewHelper::search($michael->company, 'e');
        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                ],
            ],
            $collection->toArray()
        );

        $collection = AdminExpenseViewHelper::search($michael->company, 'bli');
        $this->assertEquals(0, $collection->count());
        $this->assertEquals(
            [],
            $collection->toArray()
        );
    }
}
