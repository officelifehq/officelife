<?php

namespace Tests\Unit\Services\Company\Employee\ECoffee;

use Tests\TestCase;
use App\Models\Company\ECoffee;
use App\Models\Company\Employee;
use App\Models\Company\ECoffeeMatch;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\ECoffee\MatchEmployeesForECoffee;

class MatchEmployeesForECoffeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_matches_employees(): void
    {
        $michael = $this->createAdministrator();
        Employee::factory()->count(2)->create([
            'company_id' => $michael->company_id,
        ]);

        (new MatchEmployeesForECoffee)->execute([
            'company_id' => $michael->company_id,
        ]);

        $this->assertEquals(
            1,
            ECoffee::count()
        );

        $this->assertEquals(
            2,
            ECoffeeMatch::count()
        );
    }

    /** @test */
    public function it_creates_an_ecoffee_process_for_a_new_batch(): void
    {
        $michael = $this->createAdministrator();
        Employee::factory()->count(2)->create([
            'company_id' => $michael->company_id,
        ]);
        ECoffee::factory()->create([
            'company_id' => $michael->company_id,
            'batch_number' => 3,
        ]);

        (new MatchEmployeesForECoffee)->execute([
            'company_id' => $michael->company_id,
        ]);

        $eCoffee = ECoffee::orderBy('id', 'desc')->first();

        $this->assertEquals(
            4,
            $eCoffee->batch_number
        );

        $this->assertEquals(
            2,
            ECoffeeMatch::count()
        );

        $this->assertTrue($eCoffee->active);

        $this->assertEquals(
            1,
            ECoffee::where('active', false)->count()
        );
    }
}
