<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\MeetingDecision;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MeetingDecisionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_agenda_item(): void
    {
        $meetingDecision = MeetingDecision::factory()->create([]);
        $this->assertTrue($meetingDecision->agendaItem()->exists());
    }
}
