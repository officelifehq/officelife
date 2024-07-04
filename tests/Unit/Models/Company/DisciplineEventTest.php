<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\DisciplineEvent;
use App\Models\Company\Employee;
use App\Models\Company\File;
use Tests\TestCase;

class DisciplineEventTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_case(): void
    {
        $event = DisciplineEvent::factory()->create();
        $this->assertTrue($event->case()->exists());
    }

    /** @test */
    public function it_has_one_employee(): void
    {
        $michael = Employee::factory()->create();
        $event = DisciplineEvent::factory()->create([
            'author_id' => $michael->id,
        ]);

        $this->assertTrue($event->author()->exists());
    }

    /** @test */
    public function it_has_many_files(): void
    {
        $event = DisciplineEvent::factory()
            ->create();

        $file = File::factory()->create();
        $event->files()->sync([$file->id]);

        $this->assertTrue($event->files()->exists());
    }
}
