<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HelpCenterTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_checks_that_links_that_point_to_the_help_center_are_valid(): void
    {
        $links = config('officelife.help_links');

        $this->assertIsArray($links);

        foreach ($links as $key => $link) {
            $url = config('officelife.help_center_url').$link;
            $ch = curl_init($url);

            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $this->assertEquals(200, $retcode);
        }
    }
}
