<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\User\Pronoun;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PronounTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_translated_label(): void
    {
        $pronoun = factory(Pronoun::class)->create([
            'label' => 'he/him',
            'translation_key' => 'account.pronoun_he_him',
        ]);

        $this->assertEquals(
            'he/him',
            $pronoun->label
        );
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        $position = factory(Pronoun::class)->create([
            'label' => 'he',
            'translation_key' => 'dunder',
        ]);

        $this->assertEquals(
            [
                'id' => $position->id,
                'label' => 'dunder',
            ],
            $position->toObject()
        );
    }
}
