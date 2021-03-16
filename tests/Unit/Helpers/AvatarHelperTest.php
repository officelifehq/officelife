<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Models\Company\File;
use App\Helpers\AvatarHelper;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AvatarHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_default_avatar(): void
    {
        $michael = Employee::factory()->create();

        $this->assertEquals(
            'https://ui-avatars.com/api/?name='.$michael->name,
            AvatarHelper::getImage($michael)
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
            AvatarHelper::getImage($dwight)
        );

        $this->assertEquals(
            $file->cdn_url.'-/scale_crop/100x100/smart/',
            AvatarHelper::getImage($dwight, 100)
        );
    }
}
