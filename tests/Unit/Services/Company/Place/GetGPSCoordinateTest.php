<?php

namespace Tests\Unit\Services\Company\Place;

use Tests\TestCase;
use App\Models\Company\Place;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Place\GetGPSCoordinate;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GetGPSCoordinateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_null_if_geolocation_is_disabled()
    {
        config(['officelife.location_iq_api_key' => null]);

        $place = Place::factory()->create();

        $request = [
            'place_id' => $place->id,
        ];

        $place = (new GetGPSCoordinate)->execute($request);

        $this->assertNull($place);
    }

    /** @test */
    public function it_gets_gps_coordinates()
    {
        config(['officelife.location_iq_api_key' => 'test']);

        $body = file_get_contents(base_path('tests/Fixtures/Services/Company/Place/GetGPSCoordinateSampleResponse.json'));
        Http::fake([
            'us1.locationiq.com/v1/*' => Http::response($body, 200),
        ]);

        $place = Place::factory()->create();

        $request = [
            'place_id' => $place->id,
        ];

        $place = (new GetGPSCoordinate)->execute($request);

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
    public function it_returns_null_if_we_cant_make_the_call(): void
    {
        config(['officelife.location_iq_api_key' => 'test']);

        $place = Place::factory()->create([
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
    public function it_returns_null_if_address_is_garbage(): void
    {
        config(['officelife.location_iq_api_key' => 'test']);

        $body = file_get_contents(base_path('tests/Fixtures/Services/Company/Place/GetGPSCoordinateGarbageResponse.json'));
        Http::fake([
            'us1.locationiq.com/v1/*' => Http::response($body, 404),
        ]);

        $place = Place::factory()->create([
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
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'place_id' => 111,
        ];

        $this->expectException(ValidationException::class);
        (new GetGPSCoordinate)->execute($request);
    }
}
