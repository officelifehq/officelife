<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use ErrorException;
use Tests\TestCase;
use ArgumentCountError;
use App\Models\User\User;
use App\Models\Company\Employee;
use App\Jobs\AddEmployeeToCompany;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\ImportCSVOfUsers;

class ImportCSVOfUsersTest extends TestCase
{
    use DatabaseTransactions;

    public function getStubPath(string $name): string
    {
        return base_path("tests/Stubs/Imports/{$name}");
    }

    /** @test */
    public function it_imports_a_csv_of_users_as_administrators(): void
    {
        $michael = $this->createAdministrator();
        $this->execute($michael, 'working.csv');
    }

    /** @test */
    public function it_imports_a_csv_of_users_as_hr(): void
    {
        $michael = $this->createHR();
        $this->execute($michael, 'working.csv');
    }

    /** @test */
    public function it_cant_import_a_csv_of_users_as_normal_user(): void
    {
        $michael = $this->createEmployee();

        $this->expectException(NotEnoughPermissionException::class);
        $this->execute($michael, 'working.csv');
    }

    /** @test */
    public function it_cant_import_a_malformed_csv(): void
    {
        $michael = $this->createAdministrator();

        if (PHP_VERSION_ID >= 70100) {
            $this->expectException(ErrorException::class);
        } else {
            $this->expectException(ArgumentCountError::class);
        }
        $this->execute($michael, 'malformed.csv');
    }

    /** @test */
    public function it_does_nothing_if_it_imports_an_empty_file(): void
    {
        Queue::fake();

        $michael = $this->createAdministrator();

        (new ImportCSVOfUsers)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'path' => $this->getStubPath('empty.csv'),
        ]);

        Queue::assertNothingPushed();
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $dwight = factory(User::class)->create([]);

        $request = [
            'author_id' => $dwight->id,
        ];

        $this->expectException(ValidationException::class);
        (new ImportCSVOfUsers)->execute($request);
    }

    private function execute(Employee $michael, string $filename): void
    {
        Queue::fake();

        (new ImportCSVOfUsers)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'path' => $this->getStubPath($filename),
        ]);

        Queue::assertPushed(AddEmployeeToCompany::class, 2);
    }
}
