<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Tests\TestCase;
use App\Helpers\AvatarHelper;
use App\Models\Company\OneOnOneNote;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\OneOnOneActionItem;
use App\Models\Company\OneOnOneTalkingPoint;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Dashboard\DashboardOneOnOneViewHelper;

class DashboardOneOnOneViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_an_array_containing_all_the_information_about_a_one_on_one_entry(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);

        $entry = OneOnOneEntry::factory()->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
            'happened_at' => '2020-09-09',
        ]);

        $talkingPoint = OneOnOneTalkingPoint::factory()->create([
            'one_on_one_entry_id' => $entry->id,
        ]);

        $actionItem = OneOnOneActionItem::factory()->create([
            'one_on_one_entry_id' => $entry->id,
        ]);

        $note = OneOnOneNote::factory()->create([
            'one_on_one_entry_id' => $entry->id,
        ]);

        $array = DashboardOneOnOneViewHelper::details($entry);

        $this->assertEquals($entry->id, $array['id']);
        $this->assertEquals('Sep 09, 2020', $array['happened_at']);
        $this->assertEquals(
            [
                'id' => $entry->employee->id,
                'name' => $entry->employee->name,
                'avatar' => AvatarHelper::getImage($entry->employee),
                'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$entry->employee->id,
            ],
            $array['employee']
        );
        $this->assertEquals(
            [
                'id' => $entry->manager->id,
                'name' => $entry->manager->name,
                'avatar' => AvatarHelper::getImage($entry->manager),
                'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$entry->manager->id,
            ],
            $array['manager']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $talkingPoint->id,
                    'description' => $talkingPoint->description,
                    'checked' => false,
                ],
            ],
            $array['talking_points']->toArray()
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $actionItem->id,
                    'description' => $actionItem->description,
                    'checked' => false,
                ],
            ],
            $array['action_items']->toArray()
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $note->id,
                    'note' => $note->note,
                ],
            ],
            $array['notes']->toArray()
        );
    }
}
