<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\MeetingDecision;
use Tests\TestCase;

class MeetingDecisionTest extends TestCase
{
    /** @test */
    public function it_belongs_to_an_agenda_item(): void
    {
        $meetingDecision = MeetingDecision::factory()->create([]);
        $this->assertTrue($meetingDecision->agendaItem()->exists());
    }
}
