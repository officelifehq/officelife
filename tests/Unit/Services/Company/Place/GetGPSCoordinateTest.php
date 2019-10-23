<?php

namespace Tests\Unit\Services\Company\Place;

use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use App\Models\Company\Place;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Place\GetGPSCoordinate;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetGPSCoordinateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_null_if_geolocation_is_disabled()
    {
        config(['kakene.location_iq_api_key' => null]);

        $place = factory(Place::class)->create();

        $request = [
            'place_id' => $place->id,
        ];

        $place = (new GetGPSCoordinate)->execute($request);

        $this->assertNull($place);
    }

    /** @test */
    public function it_gets_gps_coordinates()
    {
        config(['kakene.location_iq_api_key' => 'test']);

        $body = file_get_contents(base_path('tests/Fixtures/Services/Company/Place/GetGPSCoordinateSampleResponse.json'));
        $mock = new MockHandler([new Response(200, [], $body)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $place = factory(Place::class)->create();

        $request = [
            'place_id' => $place->id,
        ];

        $place = (new GetGPSCoordinate)->execute($request, $client);

        $this->assertDatabaseHas('places', [
            'id' => $place->id,
            'latitude' => '34.0736204',
            'longitude' => '-118.4003563',
        ]);

        $this->assertInstanceOf(
            Place::class,
            $place
        );
    }

    /** @test */
    public function it_returns_null_if_we_cant_make_the_call() : void
    {
        config(['kakene.location_iq_api_key' => 'test']);

        $place = factory(Place::class)->create([
            'street' => '',
            'city' => 'sieklopekznqqq',
            'postal_code' => '',
        ]);

        $request = [
            'place_id' => $place->id,
        ];

        $place = (new GetGPSCoordinate)->execute($request);

        $this->assertNull($place);
    }

    /** @test */
    public function it_returns_null_if_address_is_garbage() : void
    {
        config(['kakene.location_iq_api_key' => 'test']);

        $body = file_get_contents(base_path('tests/Fixtures/Services/Company/Place/GetGPSCoordinateGarbageResponse.json'));
        $mock = new MockHandler([new Response(404, [], $body)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $place = factory(Place::class)->create([
            'street' => '',
            'city' => 'sieklopekznqqq',
            'postal_code' => '',
        ]);

        $request = [
            'place_id' => $place->id,
        ];

        $place = (new GetGPSCoordinate)->execute($request, $client);

        $this->assertNull($place);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'place_id' => 111,
        ];

        $this->expectException(ValidationException::class);
        (new GetGPSCoordinate)->execute($request);
    }
}
