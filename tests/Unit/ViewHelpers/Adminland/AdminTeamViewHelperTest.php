<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\ApiTestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Team;
use App\Models\Company\TeamLog;
use GrahamCampbell\TestBenchCore\HelperTrait;
use App\Http\ViewHelpers\Adminland\AdminTeamViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AdminTeamViewHelperTest extends ApiTestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_a_collection_of_teams(): void
    {
        $michael = $this->createAdministrator();
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $collection = AdminTeamViewHelper::teams($michael->company->teams);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/teams/'.$team->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_list_of_audit_logs(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $auditLogA = TeamLog::factory()->create([
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'team_id' => $team->id,
            'audited_at' => '2020-01-12 00:00:00',
        ]);
        TeamLog::factory()->create([
            'author_id' => $dwight->id,
            'author_name' => $dwight->name,
            'team_id' => $team->id,
            'audited_at' => '2020-01-12 00:00:00',
        ]);

        $logs = $team->logs()->with('author')->paginate(15);
        $collection = AdminTeamViewHelper::logs($logs);

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
                    'url' => env('APP_URL').'/'.$auditLogA->author->company_id.'/employees/'.$auditLogA->author->id,
                ],
                'localized_audited_at' => 'Jan 12, 2020 12:00 AM',
                'audited_at' => $auditLogA->audited_at,
            ],
            $collection[0]
        );
    }
}
