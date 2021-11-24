<?php

namespace Tests\Unit\ViewHelpers\Dashboard\HR;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\DisciplineCase;
use App\Models\Company\DisciplineEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRDisciplineEventViewHelper;

class DashboardHRDisciplineEventViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_data_transfer_object(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();
        $case = DisciplineCase::factory()->create([
            'company_id' => $michael->company_id,
            'active' => true,
        ]);
        $event = DisciplineEvent::factory()->create([
            'discipline_case_id' => $case->id,
            'author_id' => $michael->id,
            'happened_at' => Carbon::now(),
            'description' => 'this is a description',
        ]);

        $array = DashboardHRDisciplineEventViewHelper::dto($michael->company, $case, $event);

        $this->assertEquals(
            [
                'id' => $event->id,
                'happened_at' => 'Jan 01, 2018',
                'description' => '<p>this is a description</p>',
                'author' => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 40),
                    'position' => $michael->position->title,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
                'url' => [
                    'update' => env('APP_URL').'/'.$michael->company_id.'/dashboard/hr/discipline-cases/'.$case->id.'/events/'.$event->id.'/update',
                    'delete' => env('APP_URL').'/'.$michael->company_id.'/dashboard/hr/discipline-cases/'.$case->id.'/events/'.$event->id,
                ],
            ],
            $array
        );
    }
}
