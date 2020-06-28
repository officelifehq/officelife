<?php

namespace Tests\Unit\Services\Company\Adminland\ExpenseCategory;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\EmployeeStatus;
use App\Models\Company\ExpenseCategory;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\ExpenseCategory\UpdateExpenseCategory;

class UpdateExpenseCategoryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_an_expense_category_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $category = factory(ExpenseCategory::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $category);
    }

    /** @test */
    public function it_updates_an_expense_category_as_hr(): void
    {
        $michael = $this->createHR();
        $category = factory(ExpenseCategory::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $category);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $category = factory(ExpenseCategory::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $category);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateExpenseCategory)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_expense_category_does_not_match_the_company(): void
    {
        $employeeStatus = factory(EmployeeStatus::class)->create([]);
        $michael = $this->createAdministrator();
        $category = factory(ExpenseCategory::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $category);
    }

    private function executeService(Employee $michael, ExpenseCategory $category): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'expense_category_id' => $category->id,
            'name' => 'Non permanent',
        ];

        $newCategory = (new UpdateExpenseCategory)->execute($request);

        $this->assertDatabaseHas('expense_categories', [
            'id' => $category->id,
            'company_id' => $category->company_id,
            'name' => 'Non permanent',
        ]);

        $this->assertInstanceOf(
            ExpenseCategory::class,
            $newCategory
        );

        $this->assertEquals(
            $newCategory->id,
            $category->id
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $category) {
            return $job->auditLog['action'] === 'expense_category_updated' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'expense_category_id' => $category->id,
                    'expense_category_old_name' => $category->name,
                    'expense_category_new_name' => 'Non permanent',
                ]);
        });
    }
}
