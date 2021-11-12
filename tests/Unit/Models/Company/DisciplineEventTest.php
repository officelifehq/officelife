<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Employee;
use App\Models\Company\DisciplineEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DisciplineEventTest extends TestCase
{
    use DatabaseTransactions;

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
