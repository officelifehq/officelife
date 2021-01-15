<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\User\Avatar;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AvatarTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $avatar = Avatar::factory()->create([]);
        $this->assertTrue($avatar->company()->exists());
    }

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $avatar = Avatar::factory()->create([]);
        $this->assertTrue($avatar->employee()->exists());
    }
}
