<?php

namespace Tests\Unit\Services\Company\Employee\Team;

use Tests\TestCase;
use App\Models\Company\Place;
use App\Models\Company\Country;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Place\CreatePlace;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreatePlaceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_place() : void
    {
        Queue::fake();

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
        ];

        $place = (new CreatePlace)->execute($request);

        $this->assertDatabaseHas('places', [
            'id' => $place->id,
            'street' => '13 rue des champs',
            'city' => 'Montreal',
            'province' => 'QC',
            'postal_code' => 'H2L2X3',
            'country_id' => $country->id,
        ]);

        $this->assertInstanceOf(
            Place::class,
            $place
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
