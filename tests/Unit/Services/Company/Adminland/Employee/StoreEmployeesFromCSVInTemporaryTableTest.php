<?php

namespace Tests\Unit\Services\Company\Adminland\Employee;

use Tests\TestCase;
use App\Jobs\ServiceQueue;
use App\Models\Company\File;
use Illuminate\Support\Facades\Queue;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\Employee\ImportEmployeesFromCSV;
use App\Services\Company\Adminland\Employee\StoreEmployeesFromCSVInTemporaryTable;

class StoreEmployeesFromCSVInTemporaryTableTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_store_employees_from_csv(): void
    {
        Queue::fake();

        $employee = $this->createHR();
        $file = File::factory()->create([
            'company_id' => $employee->company_id,
        ]);

        (new StoreEmployeesFromCSVInTemporaryTable)->execute([
            'company_id' => $employee->company_id,
            'author_id' => $employee->id,
            'file_id' => $file->id,
        ]);

        $this->assertDatabaseHas('import_jobs', [
            'company_id' => $employee->company_id,
            'author_id' => $employee->id,
            'status' => 'created',
        ]);

        Queue::assertPushed(function (ServiceQueue $job) {
            return $job->service instanceof ImportEmployeesFromCSV;
        });
    }

    /** @test */
    public function it_fails_if_file_does_not_match_the_company(): void
    {
        $employee = $this->createHR();
        $file = File::factory()->create();

        $this->expectException(ModelNotFoundException::class);
        (new StoreEmployeesFromCSVInTemporaryTable)->execute([
            'company_id' => $employee->company_id,
            'author_id' => $employee->id,
            'file_id' => $file->id,
        ]);
    }

    /** @test */
    public function it_fails_if_employee_not_hr(): void
    {
        $employee = $this->createEmployee();
        $file = File::factory()->create([
            'company_id' => $employee->company_id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        (new StoreEmployeesFromCSVInTemporaryTable)->execute([
            'company_id' => $employee->company_id,
            'author_id' => $employee->id,
            'file_id' => $file->id,
        ]);
    }
}
