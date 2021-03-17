<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\File;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminGeneralViewHelper;

class AdminGeneralViewHelperTest extends TestCase
{
    use DatabaseTransactions,
        HelperTrait;

    /** @test */
    public function it_gets_information_about_the_company(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $dwight->permission_level = 100;
        $dwight->save();

        File::factory()->count(3)->create([
            'company_id' => $michael->company_id,
            'size' => 123,
        ]);

        $michael->company->logo_file_id = File::factory()->create([
            'company_id' => $michael->company_id,
            'size' => 123,
        ])->id;
        $michael->company->save();

        $response = AdminGeneralViewHelper::information($michael->company);

        $this->assertEquals(
            $michael->company_id,
            $response['id']
        );

        $this->assertEquals(
            $michael->company->name,
            $response['name']
        );

        $this->assertEquals(
            'Jan 01, 2018 00:00',
            $response['creation_date']
        );

        $this->assertEquals(
            'USD',
            $response['currency']
        );

        $this->assertEquals(
            0.492,
            $response['total_size']
        );

        $this->assertEquals(
            ImageHelper::getImage($michael->company->logo, 300, 300),
            $response['logo']
        );

        $response['administrators']->sortBy('id');

        $this->assertEquals(
            [
                'id' => $michael->id,
                'name' => $michael->name,
                'avatar' => ImageHelper::getAvatar($michael),
                'url_view' => route('employees.show', [
                    'company' => $michael->company,
                    'employee' => $michael,
                ]),
            ],
            $response['administrators']->toArray()[0]
        );

        $this->assertArrayHasKey('id', $response);
        $this->assertArrayHasKey('name', $response);
        $this->assertArrayHasKey('administrators', $response);
        $this->assertArrayHasKey('creation_date', $response);
        $this->assertArrayHasKey('currency', $response);
    }

    /** @test */
    public function it_gets_a_collection_of_currencies(): void
    {
        $response = AdminGeneralViewHelper::currencies();

        $this->assertEquals(
            179,
            $response->count()
        );
    }
}
