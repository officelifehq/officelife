<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Hardware\LendHardware;
use App\Http\ViewHelpers\Adminland\AdminHardwareViewHelper;
use App\Services\Company\Adminland\Hardware\CreateHardware;
use App\Services\Company\Adminland\Hardware\UpdateHardware;

class AdminHardwareViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_a_null_if_there_are_no_hardware_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $hardware = $michael->hardware;

        $this->assertNull(
            AdminHardwareViewHelper::hardware($hardware)
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

        $hardware = $michael->company->hardware()->with('employee')->orderBy('created_at', 'desc')->get();
        $response = AdminHardwareViewHelper::hardware($hardware);

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

    /** @test */
    public function it_gets_a_collection_of_all_employees_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        factory(Employee::class, 3)->create([
            'company_id' => $michael->company_id,
        ]);

        $response = AdminHardwareViewHelper::employeesList($michael->company);

        $this->assertCount(
            4,
            $response
        );

        $this->assertArraySubset(
            [
                'value' => $michael->id,
                'label' => $michael->name,
            ],
            $response->toArray()[0]
        );
    }

    /** @test */
    public function it_gets_the_information_about_available_hardware_in_the_company(): void
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

        $hardware = $michael->company->hardware()->with('employee')->orderBy('created_at', 'desc')->get();
        $response = AdminHardwareViewHelper::availableHardware($hardware);

        $this->assertCount(
            1,
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

        $this->assertArrayHasKey('hardware_collection', $response);
        $this->assertArrayHasKey('number_hardware_not_lent', $response);
        $this->assertArrayHasKey('number_hardware_lent', $response);
    }

    /** @test */
    public function it_gets_the_information_about_lent_hardware_in_the_company(): void
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

        $hardware = $michael->company->hardware()->with('employee')->orderBy('created_at', 'desc')->get();
        $response = AdminHardwareViewHelper::lentHardware($hardware);

        $this->assertCount(
            1,
            $response['hardware_collection']
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
            $response['hardware_collection'][0]
        );

        $this->assertArrayHasKey('hardware_collection', $response);
        $this->assertArrayHasKey('number_hardware_not_lent', $response);
        $this->assertArrayHasKey('number_hardware_lent', $response);
    }

    /** @test */
    public function it_gets_the_complete_history_of_the_hardware(): void
    {
        $michael = $this->createAdministrator();
        $hardware = (new CreateHardware)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'name' => 'iphone',
            'serial_number' => '1234',
        ]);

        (new UpdateHardware)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'hardware_id' => $hardware->id,
            'name' => 'iphone',
            'serial_number' => '1234',
        ]);

        (new LendHardware)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'hardware_id' => $hardware->id,
            'employee_id' => $michael->id,
        ]);

        $response = AdminHardwareViewHelper::history($hardware);

        $this->assertCount(
            3,
            $response
        );
    }
}
