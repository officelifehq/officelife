<?php

namespace Tests\Unit\Models\User;

use App\Models\User\Pronoun;
use Tests\TestCase;

class PronounTest extends TestCase
{
    /** @test */
    public function it_returns_the_translated_label(): void
    {
        $pronoun = Pronoun::factory()->create([
            'label' => 'he/him',
            'translation_key' => 'account.pronoun_he_him',
        ]);

        $this->assertEquals(
            'he/him',
            $pronoun->label
        );
    }
}
