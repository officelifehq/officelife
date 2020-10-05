<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\StringHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StringHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_parsed_content(): void
    {
        $content = '**hi**';

        $this->assertEquals(
            '<p><strong>hi</strong></p>',
            StringHelper::parse($content)
        );
    }
}
