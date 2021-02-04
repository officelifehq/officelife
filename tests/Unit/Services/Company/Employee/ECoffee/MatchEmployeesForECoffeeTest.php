<?php

namespace Tests\Unit\Services\Company\Employee\ECoffee;

use Tests\TestCase;
use App\Models\Company\Employee;
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
    }
}
