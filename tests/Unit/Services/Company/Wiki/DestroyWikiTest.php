<?php

namespace Tests\Unit\Services\Company\Wiki;

use Tests\TestCase;
use App\Models\Company\Wiki;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Wiki\DestroyWiki;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyWikiTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_wiki_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $wiki);
    }

    /** @test */
    public function it_deletes_a_wiki_as_hr(): void
    {
        $michael = $this->createHR();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $wiki);
    }

    /** @test */
    public function it_deletes_a_wiki_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $wiki = Wiki::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $this->executeService($michael, $wiki);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyWiki)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_wiki_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $wiki = Wiki::factory()->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $wiki);
    }

    private function executeService(Employee $michael, Wiki $wiki): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'wiki_id' => $wiki->id,
        ];

        (new DestroyWiki)->execute($request);

        $this->assertDatabaseMissing('wikis', [
            'id' => $wiki->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $wiki) {
            return $job->auditLog['action'] === 'wiki_destroyed' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'wiki_title' => $wiki->title,
                ]);
        });
    }
}
