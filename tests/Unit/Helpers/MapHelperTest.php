<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\MapHelper;
use App\Models\Company\Place;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MapHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_a_static_map_url(): void
    {
        config(['officelife.mapbox_api_key' => 'api_key']);
        config(['officelife.mapbox_api_username' => 'test']);

        $place = factory(Place::class)->create([
            'longitude' => '-74.005941',
            'latitude' => '40.712784',
        ]);

        $url = MapHelper::getStaticImage($place, 300, 300, 7);

        $this->assertEquals(
            'https://api.mapbox.com/styles/v1/test/ck335w8te1vzj1cn7aszafhm2/static/-74.005941,40.712784,7/300x300@2x?access_token=api_key',
            $url
        );
    }

    /** @test */
    public function it_cant_return_a_map_without_the_api_key_env_variable(): void
    {
        config(['officelife.mapbox_api_key' => null]);
        config(['officelife.mapbox_api_username' => 'test']);

        $place = factory(Place::class)->create([
            'longitude' => '-74.005941',
            'latitude' => '40.712784',
        ]);

        $url = MapHelper::getStaticImage($place, 300, 300, 7);

        $this->assertNull($url);
    }

    /** @test */
    public function it_cant_return_a_map_without_the_username_env_variable(): void
    {
        config(['officelife.mapbox_api_key' => 'api_key']);
        config(['officelife.mapbox_api_username' => null]);

        $place = factory(Place::class)->create([
            'longitude' => '-74.005941',
            'latitude' => '40.712784',
        ]);

        $url = MapHelper::getStaticImage($place, 300, 300, 7);

        $this->assertNull($url);
    }
}
