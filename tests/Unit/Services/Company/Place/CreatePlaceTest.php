<?php

namespace Tests\Unit\Services\Company\Place;

use Tests\TestCase;
use App\Models\Company\Place;
use App\Models\Company\Country;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use App\Jobs\FetchAddressGeocoding;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Place\CreatePlace;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreatePlaceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_place_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_place_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_place_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_sets_all_previous_places_to_inactive(): void
    {
        $michael = Employee::factory()->create([]);
        $country = Country::factory()->create([]);
        Place::factory()->count(3)->create([
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
    public function it_doesnt_set_the_place_to_active(): void
    {
        $michael = Employee::factory()->create([]);
        $country = Country::factory()->create([]);
        Place::factory()->count(3)->create([
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
            'is_active' => false,
        ];

        $place = (new CreatePlace)->execute($request);

        $numberOfActivePlaces = DB::table('places')->where('placable_id', $place->placable_id)
            ->where('placable_type', $place->placable_type)
            ->where('is_active', true)
            ->count();

        $this->assertEquals(
            3,
            $numberOfActivePlaces
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = Employee::factory()->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreatePlace)->execute($request);
    }

    private function executeService(Employee $michael, bool $setActive = false): void
    {
        Queue::fake();

        $country = Country::factory()->create([]);

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
            'is_active' => $setActive ? true : null,
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

        if ($setActive) {
            $this->assertEquals(
                1,
                $numberOfActivePlaces
            );
        } else {
            $this->assertEquals(
                0,
                $numberOfActivePlaces
            );
        }

        Queue::assertPushed(FetchAddressGeocoding::class, function ($job) use ($place) {
            return $job->place->id === $place->id;
        });
    }
}
