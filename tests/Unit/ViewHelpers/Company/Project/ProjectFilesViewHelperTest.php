<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\File;
use App\Models\Company\Project;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Project\ProjectFilesViewHelper;

class ProjectFilesViewHelperTest extends TestCase
{
    use DatabaseTransactions, HelperTrait;

    /** @test */
    public function it_gets_a_collection_of_files(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1));

        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
            'size' => 123,
            'uploader_employee_id' => $michael->id,
        ]);
        $fileWithoutUploader = File::factory()->create([
            'company_id' => $michael->company_id,
            'size' => 123,
            'uploader_name' => 'Regis',
        ]);
        $project->files()->syncWithoutDetaching([
            $file->id,
        ]);
        $project->files()->syncWithoutDetaching([
            $fileWithoutUploader->id,
        ]);

        $collection = ProjectFilesViewHelper::index($project, $michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => $fileWithoutUploader->id,
                    'size' => '123B',
                    'filename' => $fileWithoutUploader->name,
                    'download_url' => $fileWithoutUploader->cdn_url,
                    'uploader' => 'Regis',
                    'created_at' => 'Jan 01, 2019',
                ],
                1 => [
                    'id' => $file->id,
                    'size' => '123B',
                    'filename' => $file->name,
                    'download_url' => $file->cdn_url,
                    'uploader' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => ImageHelper::getAvatar($michael, 24),
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                    'created_at' => 'Jan 01, 2019',
                ],
            ],
            $collection->toArray()
        );
    }
}
