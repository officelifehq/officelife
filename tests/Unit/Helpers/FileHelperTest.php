<?php

namespace Tests\Unit\Helpers;

use App\Helpers\FileHelper;
use Tests\TestCase;

class FileHelperTest extends TestCase
{
    /** @test */
    public function it_returns_the_file_size_in_the_right_format(): void
    {
        $this->assertEquals(
            '12.61MB',
            FileHelper::getSize(13223239)
        );
        $this->assertEquals(
            '12.93kB',
            FileHelper::getSize(13240)
        );
    }
}
