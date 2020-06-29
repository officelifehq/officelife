<?php

namespace Tests\Unit\Services\Company\Adminland\ExpenseCategory;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\ExpenseCategory;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\ExpenseCategory\DestroyExpenseCategory;

class DestroyExpenseCategoryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_an_expense_category_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $category = factory(ExpenseCategory::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $category);
    }

    /** @test */
    public function it_destroys_an_expense_category_as_hr(): void
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
        (new DestroyExpenseCategory)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_expense_category_does_not_match_the_company(): void
    {
        $michael = $this->createAdministrator();
        $category = factory(ExpenseCategory::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $category);
    }

    private function executeService(Employee $michael, ExpenseCategory $category): void
    {
        Queue::fake();

        $request = [
            'company_id' => $category->company_id,
            'author_id' => $michael->id,
            'expense_category_id' => $category->id,
        ];

        (new DestroyExpenseCategory)->execute($request);

        $this->assertDatabaseMissing('expense_categories', [
            'id' => $category->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $category) {
            return $job->auditLog['action'] === 'expense_category_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'expense_category_name' => $category->name,
                ]);
        });
    }
}
