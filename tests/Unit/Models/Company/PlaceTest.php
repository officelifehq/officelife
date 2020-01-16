<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Place;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlaceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_one_country(): void
    {
        $place = factory(Place::class)->create([]);
        $this->assertTrue($place->country()->exists());
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        $news = factory(Place::class)->create([
            'street' => '1725 Slough Ave',
            'city' => 'Scranton',
            'province' => 'PA',
            'postal_code' => '',
            'latitude' => '1',
            'longitude' => '2',
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $news->id,
                'readable' => $news->getCompleteAddress(),
                'partial' => $news->getPartialAddress(),
                'street' => $news->street,
                'city' => $news->city,
                'province' => $news->province,
                'postal_code' => $news->postal_code,
                'country' => $news->country,
                'openstreetmap_url' => $news->getMapUrl(),
                'employee_cover_image_url' => $news->getStaticMapImage(7, 600, 130),
                'created_at' => '2020-01-12 00:00:00',
            ],
            $news->toObject()
        );
    }

    /** @test */
    public function it_gets_the_country_name(): void
    {
        $place = factory(Place::class)->create([]);

        $this->assertEquals(
            'France',
            $place->getCountryName()
        );
    }

    /** @test */
    public function it_gets_the_address_as_a_string(): void
    {
        $place = factory(Place::class)->create([]);

        $this->assertEquals(
            '1725 Slough Ave Scranton PA France',
            $place->getCompleteAddress()
        );
    }

    /** @test */
    public function it_gets_the_partial_address_as_a_string(): void
    {
        $place = factory(Place::class)->create([]);

        $this->assertEquals(
            'Scranton (France)',
            $place->getPartialAddress()
        );
    }

    /** @test */
    public function it_gets_a_static_image_map(): void
    {
        config(['officelife.mapbox_api_key' => 'api_key']);
        config(['officelife.mapbox_username' => 'test']);

        $place = factory(Place::class)->create([
            'longitude' => '-74.005941',
            'latitude' => '40.712784',
        ]);

        $this->assertEquals(
            'https://api.mapbox.com/styles/v1/test/ck335w8te1vzj1cn7aszafhm2/static/-74.005941,40.712784,7/300x300@2x?access_token=api_key',
            $place->getStaticMapImage(7, 300, 300)
        );
    }

    /** @test */
    public function it_returns_an_open_streetmap_url()
    {
        $place = factory(Place::class)->create([]);

        $this->assertEquals(
            'https://www.openstreetmap.org/search?query=Scranton+%28France%29',
            $place->getMapUrl()
        );
    }
}
