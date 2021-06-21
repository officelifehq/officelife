<?php

namespace Tests\Unit\Services\Company\Wiki;

use Tests\TestCase;
use App\Models\Company\Wiki;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Wiki\CreateWiki;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateWikiTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_wiki_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_wiki_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_wiki_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateWiki)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'title' => 'Livraison API v3',
        ];

        $wiki = (new CreateWiki)->execute($request);

        $this->assertDatabaseHas('wikis', [
            'id' => $wiki->id,
            'company_id' => $michael->company_id,
            'title' => 'Livraison API v3',
        ]);

        $this->assertInstanceOf(
            Wiki::class,
            $wiki
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $wiki) {
            return $job->auditLog['action'] === 'wiki_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'wiki_id' => $wiki->id,
                    'wiki_title' => $wiki->title,
                ]);
        });
    }
}
