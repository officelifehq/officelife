<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use App\Http\ViewHelpers\Adminland\AdminProjectManagementViewHelper;
use App\Models\Company\Company;
use App\Models\Company\IssueType;
use Tests\ApiTestCase;

class AdminProjectManagementViewHelperTest extends ApiTestCase
{
    /** @test */
    public function it_gets_all_the_issue_types(): void
    {
        $company = Company::factory()->create();
        $type = IssueType::factory()->create([
            'company_id' => $company->id,
            'name' => 'supername',
            'icon_hex_color' => '#123',
        ]);

        $array = AdminProjectManagementViewHelper::issueTypes($company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $type->id,
                    'name' => 'supername',
                    'icon_hex_color' => '#123',
                    'url' => [
                        'update' => env('APP_URL').'/'.$company->id.'/account/project/issueType/'.$type->id,
                        'destroy' => env('APP_URL').'/'.$company->id.'/account/project/issueType/'.$type->id,
                    ],
                ],
            ],
            $array['issue_types']->toArray()
        );

        $this->assertEquals(
            env('APP_URL').'/'.$company->id.'/account/project/issueType',
            $array['url_create']
        );
    }
}
