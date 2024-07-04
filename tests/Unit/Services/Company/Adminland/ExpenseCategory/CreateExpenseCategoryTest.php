<?php

namespace Tests\Unit\Services\Company\Adminland\ExpenseCategory;

use App\Exceptions\NotEnoughPermissionException;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\ExpenseCategory;
use App\Services\Company\Adminland\ExpenseCategory\CreateExpenseCategory;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateExpenseCategoryTest extends TestCase
{
    /** @test */
    public function it_creates_an_expense_category_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_an_expense_category_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'travel',
        ];

        $this->expectException(ValidationException::class);
        (new CreateExpenseCategory)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'travel',
        ];

        $category = (new CreateExpenseCategory)->execute($request);

        $this->assertDatabaseHas('expense_categories', [
            'id' => $category->id,
            'company_id' => $michael->company_id,
            'name' => 'travel',
        ]);

        $this->assertInstanceOf(
            ExpenseCategory::class,
            $category
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $category) {
            return $job->auditLog['action'] === 'expense_category_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'expense_category_id' => $category->id,
                    'expense_category_name' => 'travel',
                ]);
        });
    }
}
