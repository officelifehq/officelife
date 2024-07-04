<?php

namespace Tests\Unit\Helpers;

use App\Helpers\SearchHelper;
use Tests\TestCase;

class SearchHelperTest extends TestCase
{
    /** @test */
    public function it_builds_a_sql_query(): void
    {
        $array = [
            'column1',
            'column2',
            'column3',
        ];

        $searchTerm = 'term';

        $this->assertEquals(
            "column1 LIKE '%term%' or column2 LIKE '%term%' or column3 LIKE '%term%'",
            SearchHelper::buildQuery($array, $searchTerm)
        );
    }
}
