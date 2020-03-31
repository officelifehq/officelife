<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Helpers\PaginatorHelper;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaginatorHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_an_array_containing_everything_needed_for_a_pagination()
    {
        $company = factory(Company::class)->create([]);
        factory(Employee::class, 3)->create(['company_id' => $company->id]);

        $employees = $company->employees()->paginate(1);

        $this->assertEquals(
            [
                'count' => 1,
                'currentPage' => 1,
                'firstItem' => 1,
                'hasMorePages' => true,
                'lastItem' => 1,
                'lastPage' => 3,
                'nextPageUrl' => config('app.url').'?page=2',
                'onFirstPage' => true,
                'perPage' => 1,
                'previousPageUrl' => null,
                'total' => 3,
            ],
            PaginatorHelper::getData($employees)
        );
    }
}
