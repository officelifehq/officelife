<?php

namespace Tests\Unit\Helpers;

use App\Helpers\StringHelper;
use Tests\TestCase;

class StringHelperTest extends TestCase
{
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
