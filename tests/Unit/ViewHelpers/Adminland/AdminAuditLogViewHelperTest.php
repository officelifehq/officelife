<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\AuditLog;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminAuditLogViewHelper;

class AdminAuditLogViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_the_list_of_audit_logs(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $auditLogA = AuditLog::factory()->create([
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'company_id' => $michael->company_id,
            'audited_at' => '2020-01-12 00:00:00',
        ]);
        AuditLog::factory()->create([
            'author_id' => $dwight->id,
            'author_name' => $dwight->name,
            'company_id' => $michael->company_id,
            'audited_at' => '2020-01-12 00:00:00',
        ]);

        $logs = $michael->company->logs()->with('author')->paginate(15);
        $collection = AdminAuditLogViewHelper::index($logs, $michael);

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
                    'avatar' => ImageHelper::getAvatar($auditLogA->author, 35),
                    'url' => env('APP_URL').'/'.$auditLogA->company_id.'/employees/'.$auditLogA->author->id,
                ],
                'localized_audited_at' => 'Jan 12, 2020 00:00',
            ],
            $collection[0]
        );
    }
}
