<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Homework;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HomeworkTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee()
    {
        $homework = factory(Homework::class)->create([]);
        $this->assertTrue($homework->employee()->exists());
    }
}
