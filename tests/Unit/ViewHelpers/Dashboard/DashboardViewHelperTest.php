<?php

namespace Tests\Unit\ViewHelpers\Dashboard;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Http\ViewHelpers\Dashboard\DashboardViewHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DashboardViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_information_about_the_employee(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        $this->assertEquals(
            [
                'id' => $michael->id,
                'name' => $michael->name,
                'avatar' => ImageHelper::getAvatar($michael, 55),
                'dashboard_view' => 'info',
                'can_manage_expenses' => false,
                'is_manager' => false,
                'can_manage_hr' => true,
                'url' => env('APP_URL') . '/' . $michael->company_id . '/employees/' . $michael->id,
            ],
            DashboardViewHelper::information($michael, 'info')
        );
    }
}
