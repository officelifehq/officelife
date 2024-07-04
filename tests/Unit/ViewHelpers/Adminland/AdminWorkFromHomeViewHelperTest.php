<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use App\Http\ViewHelpers\Adminland\AdminWorkFromHomeViewHelper;
use App\Models\Company\Company;
use Tests\TestCase;

class AdminWorkFromHomeViewHelperTest extends TestCase
{
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
