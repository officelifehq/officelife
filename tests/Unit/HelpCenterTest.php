<?php

namespace Tests\Unit;

use Tests\TestCase;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Promise\Utils as GuzzleUtils;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HelpCenterTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_checks_that_links_that_point_to_the_help_center_are_valid(): void
    {
        $links = config('officelife.help_links');
        $client = new GuzzleClient();

        $this->assertIsArray($links);

        $promises = [];
        foreach ($links as $link) {
            $url = config('officelife.help_center_url').$link;

            $promises[$url] = $client->getAsync($url, [
                'http_errors' => false,
                'verify' => false,
            ]);
        }
        $responses = GuzzleUtils::unwrap($promises);
        foreach ($responses as $url => $response) {
            $this->assertEquals(200, $response->getStatusCode(), "error requesting help link $url");
        }
    }
}
