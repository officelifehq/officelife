<?php

namespace Tests\Unit\Services\User\Avatar;

use Tests\TestCase;
use App\Services\User\Avatar\GenerateAvatar;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenerateAvatarTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_an_url(): void
    {
        $request = [
            'name' => 'Matt Blob',
        ];

        $url = (new GenerateAvatar)->execute($request);

        $this->assertEquals(
            'https://ui-avatars.com/api/?name=Matt Blob',
            $url
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'size' => 200,
        ];

        $this->expectException(ValidationException::class);
        (new GenerateAvatar)->execute($request);
    }
}
