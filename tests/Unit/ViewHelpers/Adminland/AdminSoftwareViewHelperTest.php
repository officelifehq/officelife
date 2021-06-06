<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Employee;
use App\Models\Company\Software;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminSoftwareViewHelper;

class AdminSoftwareViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_information_about_software_used_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $office365 = Software::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'Office 365',
            'seats' => 9,
        ]);
        $office365->employees()->syncWithoutDetaching([$michael->id]);

        $softwares = $michael->company->softwares()->with('employees')->orderBy('id', 'desc')->get();
        $array = AdminSoftwareViewHelper::index($softwares, $michael->company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $office365->id,
                    'name' => 'Office 365',
                    'seats' => 9,
                    'remaining_seats' => 8,
                    'url' => env('APP_URL') . '/' . $michael->company_id . '/account/softwares/'.$office365->id,
                ],
            ],
            $array['softwares']->toArray()
        );

        $this->assertEquals(
            env('APP_URL') . '/' . $michael->company_id . '/account/softwares/create',
            $array['url_new']
        );
    }

    /** @test */
    public function it_gets_the_information_about_a_specific_software(): void
    {
        $michael = $this->createAdministrator();
        $office365 = Software::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'Office 365',
            'seats' => 9,
        ]);

        $array = AdminSoftwareViewHelper::show($office365);

        $this->assertEquals(
            [
                'id' => $office365->id,
                'name' => 'Office 365',
                'website' => $office365->website,
                'product_key' => $office365->product_key,
                'seats' => $office365->seats,
                'used_seats' => 0,
                'remaining_seats' => $office365->seats,
                'licensed_to_name' => $office365->licensed_to_name,
                'licensed_to_email_address' => $office365->licensed_to_email_address,
                'order_number' => $office365->order_number,
                'purchase_amount' => $office365->purchase_amount / 100,
                'currency' => $office365->currency,
                'converted_purchase_amount' => $office365->converted_purchase_amount,
                'converted_to_currency' => $office365->converted_to_currency,
                'purchased_at' => $office365->purchased_at ? DateHelper::formatDate($office365->purchased_at) : null,
                'converted_at' => $office365->converted_at ? DateHelper::formatDate($office365->converted_at) : null,
                'exchange_rate' => $office365->exchange_rate,
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_the_employees_linked_to_a_software(): void
    {
        $michael = $this->createAdministrator();
        $office365 = Software::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'Office 365',
            'seats' => 9,
        ]);
        $office365->employees()->attach([$michael->id]);

        $employees = $office365->employees()->paginate(10);
        $collection = AdminSoftwareViewHelper::seats($employees, $michael->company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 21),
                    'product_key' => null,
                    'url' => env('APP_URL') . '/' . $michael->company_id . '/employees/' . $michael->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_potential_employees(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'ale',
            'last_name' => 'ble',
            'email' => 'ale@ble',
            'permission_level' => 100,
        ]);
        $dwight = Employee::factory()->create([
            'first_name' => 'alb',
            'last_name' => 'bli',
            'email' => 'alb@bli',
            'company_id' => $michael->company_id,
        ]);
        $software = Software::factory()->create([]);
        $software->employees()->attach([$michael->id]);

        $collection = AdminSoftwareViewHelper::getPotentialEmployees($software, $michael->company, 'bli');

        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                ],
            ],
            $collection->toArray()
        );
    }
}
