<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\AgendaItem;
use App\Models\Company\Employee;
use Tests\TestCase;

class AgendaItemTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_meeting(): void
    {
        $agendaItem = AgendaItem::factory()->create([]);
        $this->assertTrue($agendaItem->meeting()->exists());
    }

    /** @test */
    public function it_has_one_presenter(): void
    {
        $dwight = Employee::factory()->create();
        $agendaItem = AgendaItem::factory()->create([
            'presented_by_id' => $dwight->id,
        ]);

        $this->assertTrue($agendaItem->presenter()->exists());
    }
}
