<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Meeting;
use App\Models\Company\Employee;
use App\Models\Company\AgendaItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MeetingTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_group(): void
    {
        $meeting = Meeting::factory()->create([]);
        $this->assertTrue($meeting->group()->exists());
    }

    /** @test */
    public function it_has_many_employees(): void
    {
        $meeting = Meeting::factory()->create();
        $dwight = Employee::factory()->create([
            'company_id' => $meeting->group->company_id,
        ]);

        $meeting->employees()->syncWithoutDetaching([$dwight->id]);

        $this->assertTrue($meeting->employees()->exists());
    }

    /** @test */
    public function it_has_many_agenda_items(): void
    {
        $meeting = Meeting::factory()->create();
        AgendaItem::factory()->count(2)->create([
            'meeting_id' => $meeting->id,
        ]);

        $this->assertTrue($meeting->agendaItems()->exists());
    }
}
