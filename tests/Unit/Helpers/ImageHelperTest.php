<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\File;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImageHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_default_avatar(): void
    {
        $michael = Employee::factory()->create();

        $this->assertEquals(
            'https://ui-avatars.com/api/?name='.$michael->name,
            ImageHelper::getAvatar($michael)
        );
    }

    /** @test */
    public function it_returns_an_avatar(): void
    {
        $file = File::factory()->create([]);
        $dwight = Employee::factory()->create([
            'avatar_file_id' => $file->id,
        ]);

        $this->assertEquals(
            $file->cdn_url,
            ImageHelper::getAvatar($dwight)
        );

        $this->assertEquals(
            $file->cdn_url.'-/scale_crop/100x100/smart/',
            ImageHelper::getAvatar($dwight, 100)
        );
    }

    /** @test */
    public function it_returns_an_image(): void
    {
        $file = File::factory()->create([]);

        $this->assertEquals(
            $file->cdn_url.'-/preview/100x100/',
            ImageHelper::getImage($file, 100, 100)
        );
    }
}
