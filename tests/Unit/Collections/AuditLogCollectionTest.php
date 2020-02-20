<?php

namespace Tests\Unit\Collections;

use Tests\TestCase;
use App\Models\Company\AuditLog;
use App\Models\Company\Employee;
use App\Http\Collections\AuditLogCollection;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuditLogCollectionTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_returns_a_collection(): void
    {
        $michael = factory(Employee::class)->create([]);
        $dwight = factory(Employee::class)->create([
            'company_id' => $michael->company_id,
        ]);
        $auditLogA = factory(AuditLog::class)->create([
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'company_id' => $michael->company_id,
            'audited_at' => '2020-01-12 00:00:00',
        ]);
        factory(AuditLog::class)->create([
            'author_id' => $dwight->id,
            'author_name' => $dwight->name,
            'company_id' => $michael->company_id,
            'audited_at' => '2020-01-12 00:00:00',
        ]);

        $logs = $michael->company->logs()->with('author')->get();
        $collection = AuditLogCollection::prepare($logs);

        $this->assertEquals(
            2,
            $collection->count()
        );

        $this->assertArraySubset(
            [
                'id' => $auditLogA->id,
                'action' => $auditLogA->action,
                'objects' => json_decode($auditLogA->objects),
                'localized_content' => $auditLogA->content,
                'author' => [
                    'id' => is_null($auditLogA->author) ? null : $auditLogA->author->id,
                    'name' => is_null($auditLogA->author) ? $auditLogA->author_name : $auditLogA->author->name,
                ],
                'localized_audited_at' => 'Jan 12, 2020 00:00',
                'audited_at' => $auditLogA->audited_at,
            ],
            $collection[0]
        );
    }
}
