<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Morale;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MoraleTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $morale = factory(Morale::class)->create([]);
        $this->assertTrue($morale->employee()->exists());
    }

    /** @test */
    public function it_returns_the_translated_emotion_attribute(): void
    {
        $morale = factory(Morale::class)->create([
            'emotion' => 1,
        ]);

        $this->assertEquals(
            'Positive',
            $morale->translated_emotion
        );
    }

    /** @test */
    public function it_returns_an_emoji(): void
    {
        $morale = factory(Morale::class)->create([
            'emotion' => 1,
        ]);

        $this->assertEquals(
            '😡 '.trans('dashboard.morale_emotion_bad'),
            $morale->emoji
        );

        $morale = factory(Morale::class)->create([
            'emotion' => 2,
        ]);

        $this->assertEquals(
            '😌 '.trans('dashboard.morale_emotion_normal'),
            $morale->emoji
        );

        $morale = factory(Morale::class)->create([
            'emotion' => 3,
        ]);

        $this->assertEquals(
            '🥳 '.trans('dashboard.morale_emotion_good'),
            $morale->emoji
        );
    }
}
