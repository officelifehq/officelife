<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Place;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PlaceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_one_country() : void
    {
        $place = factory(Place::class)->create([]);
        $this->assertTrue($place->country()->exists());
    }

    /** @test */
    public function it_gets_the_country_name() : void
    {
        $place = factory(Place::class)->create([]);

        $this->assertEquals(
            'France',
            $place->getCountryName()
        );
    }

    /** @test */
    public function it_gets_the_address_as_a_string() : void
    {
        $place = factory(Place::class)->create([]);

        $this->assertEquals(
            '1725 Slough Ave Scranton PA France',
            $place->getAddressAsString()
        );
    }

    /** @test */
    public function it_gets_the_partial_address_as_a_string() : void
    {
        $place = factory(Place::class)->create([]);

        $this->assertEquals(
            'Scranton (France)',
            $place->getPartialAddress()
        );
    }
}
