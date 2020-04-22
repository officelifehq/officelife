<?php

namespace Tests\Unit\ViewHelpers\Company\Company;

use Tests\ApiTestCase;
use App\Models\Company\Hardware;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Adminland\AdminHardwareViewHelper;

class AdminHardwareViewHelperTest extends ApiTestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_a_null_if_there_are_no_hardware_in_the_company(): void
    {
        $michael = $this->createAdministrator();

        $this->assertNull(
            AdminHardwareViewHelper::hardware($michael->company)
        );
    }

    /** @test */
    public function it_gets_the_information_about_hardware_used_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $androidPhone = factory(Hardware::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'Android phone',
        ]);
        $iosPhone = factory(Hardware::class)->create([
            'company_id' => $michael->company_id,
            'name' => 'iOS phone',
            'employee_id' => $michael->id,
        ]);

        $response = AdminHardwareViewHelper::hardware($michael->company);

        $this->assertCount(
            2,
            $response['hardware_collection']
        );

        $this->assertArraySubset(
            [
                'id' => $androidPhone->id,
                'name' => 'Android phone',
                'employee' => null,
            ],
            $response['hardware_collection'][0]
        );

        $this->assertArraySubset(
            [
                'id' => $iosPhone->id,
                'name' => 'iOS phone',
                'employee' => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => $michael->avatar,
                ],
            ],
            $response['hardware_collection'][1]
        );

        $this->assertArrayHasKey('hardware_collection', $response);
        $this->assertArrayHasKey('number_hardware_not_lent', $response);
        $this->assertArrayHasKey('number_hardware_lent', $response);
    }
}
