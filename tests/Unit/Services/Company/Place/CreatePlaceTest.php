<?php

namespace Tests\Unit\Services\Company\Employee\Team;

use Tests\TestCase;
use App\Models\Company\Place;
use App\Models\Company\Country;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use App\Services\Company\Place\CreatePlace;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreatePlaceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_place() : void
    {
        $michael = factory(Employee::class)->create([]);
        $country = factory(Country::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'street' => '13 rue des champs',
            'city' => 'Montreal',
            'province' => 'QC',
            'postal_code' => 'H2L2X3',
            'country_id' => $country->id,
            'placable_id' => $michael->id,
            'placable_type' => 'App\Models\Company\Employee',
        ];

        $place = (new CreatePlace)->execute($request);

        $this->assertDatabaseHas('places', [
            'id' => $place->id,
            'street' => '13 rue des champs',
            'city' => 'Montreal',
            'province' => 'QC',
            'postal_code' => 'H2L2X3',
            'country_id' => $country->id,
            'placable_id' => $michael->id,
            'placable_type' => 'App\Models\Company\Employee',
            'is_active' => false,
        ]);

        $this->assertInstanceOf(
            Place::class,
            $place
        );

        $numberOfActivePlaces = DB::table('places')->where('placable_id', $place->placable_id)
            ->where('placable_type', $place->placable_type)
            ->where('is_active', true)
            ->count();

        $this->assertEquals(
            0,
            $numberOfActivePlaces
        );
    }

    /** @test */
    public function it_sets_all_previous_places_to_inactive(): void
    {
        $michael = factory(Employee::class)->create([]);
        $country = factory(Country::class)->create([]);
        factory(Place::class, 3)->create([
            'placable_id' => $michael->id,
            'placable_type' => 'App\Models\Company\Employee',
            'is_active' => true,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'street' => '13 rue des champs',
            'city' => 'Montreal',
            'province' => 'QC',
            'postal_code' => 'H2L2X3',
            'country_id' => $country->id,
            'placable_id' => $michael->id,
            'placable_type' => 'App\Models\Company\Employee',
            'is_active' => true,
        ];

        $place = (new CreatePlace)->execute($request);

        $this->assertDatabaseHas('places', [
            'id' => $place->id,
            'street' => '13 rue des champs',
            'city' => 'Montreal',
            'province' => 'QC',
            'postal_code' => 'H2L2X3',
            'country_id' => $country->id,
            'placable_id' => $michael->id,
            'placable_type' => 'App\Models\Company\Employee',
            'is_active' => true,
        ]);

        $numberOfActivePlaces = DB::table('places')->where('placable_id', $place->placable_id)
            ->where('placable_type', $place->placable_type)
            ->where('is_active', true)
            ->count();

        $this->assertEquals(
            1,
            $numberOfActivePlaces
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreatePlace)->execute($request);
    }
}
