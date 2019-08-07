<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Company;
use App\Jobs\Logs\LogAccountAudit;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\CreateCompany;

class CreateCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_company() : void
    {
        Queue::fake();

        $author = factory(User::class)->create([]);

        $request = [
            'author_id' => $author->id,
            'name' => 'Dunder Mifflin',
        ];

        $company = (new CreateCompany)->execute($request);

        $this->assertInstanceOf(
            Company::class,
            $company
        );

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Dunder Mifflin',
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($author) {
            return $job->auditLog['action'] === 'account_created' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $author->id,
                    'author_name' => $author->name,
                    'company_name' => 'Dunder Mifflin',
                ]);
        });

        // it has one employee
        $this->assertDatabaseHas('employees', [
            'company_id' => $company->id,
            'user_id' => $author->id,
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $author = factory(User::class)->create([]);

        $request = [
            'author_id' => $author->id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateCompany)->execute($request);
    }
}
