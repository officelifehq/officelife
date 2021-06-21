<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Models\Company\Company;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminWorkFromHomeViewHelper;

class AdminWorkFromHomeViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_information_about_the_work_from_home_process(): void
    {
        $company = Company::factory()->create([
            'work_from_home_enabled' => true,
        ]);

        $array = AdminWorkFromHomeViewHelper::index($company);

        $this->assertEquals(
            [
                'enabled' => true,
            ],
            $array
        );
    }
}
