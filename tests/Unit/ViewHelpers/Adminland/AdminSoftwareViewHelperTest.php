<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Tests\TestCase;
use App\Models\Company\Software;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminSoftwareViewHelper;

class AdminSoftwareViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

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
        $collection = AdminSoftwareViewHelper::index($softwares, $michael->company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $office365->id,
                    'name' => 'Office 365',
                    'seats' => 9,
                    'remaining_seats' => 8,
                    'url' => env('APP_URL') . '/' . $michael->company_id . '/account/software/'.$office365->id,
                ],
            ],
            $collection->toArray()
        );
    }
}
