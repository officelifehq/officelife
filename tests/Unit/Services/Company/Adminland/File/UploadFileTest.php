<?php

namespace Tests\Unit\Services\Company\Adminland\File;

use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Employee;
use App\Exceptions\EnvVariablesNotSetException;
use App\Services\Company\Adminland\File\UploadFile;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploadFileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_file_object_as_administrator(): void
    {
        config(['officelife.uploadcare_public_key' => 'test']);
        config(['officelife.uploadcare_private_key' => 'test']);

        $michael = Employee::factory()->asAdministrator()->create();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_file_object_as_hr(): void
    {
        config(['officelife.uploadcare_public_key' => 'test']);
        config(['officelife.uploadcare_private_key' => 'test']);

        $michael = Employee::factory()->asHR()->create();
        $this->executeService($michael);
    }

    /** @test */
    public function it_creates_a_file_object_as_normal_user(): void
    {
        config(['officelife.uploadcare_public_key' => 'test']);
        config(['officelife.uploadcare_private_key' => 'test']);

        $michael = Employee::factory()->asNormalEmployee()->create();
        $this->executeService($michael);
    }

    /** @test */
    public function it_throws_an_exception_when_env_keys_are_not_set(): void
    {
        $michael = $this->createAdministrator();
        $this->expectException(EnvVariablesNotSetException::class);
        $this->executeService($michael);

        config(['officelife.uploadcare_public_key' => 'test']);
        $this->expectException(EnvVariablesNotSetException::class);
        $this->executeService($michael);

        config(['officelife.uploadcare_private_key' => 'test']);
        $this->executeService($michael);
    }

    private function executeService(Employee $michael): void
    {
        $payload = file_get_contents(base_path('tests/Fixtures/Services/Company/File/UploadFileSampleResponse.json'));

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'payload' => $payload,
            'type' => 'avatar',
        ];

        $file = (new UploadFile)->execute($request);

        $this->assertInstanceOf(
            File::class,
            $file
        );

        $this->assertDatabaseHas('files', [
            'id' => $file->id,
            'uuid' => '017162da-e83b-46fc-89fc-3a7740db0a82',
            'url' => 'https://api.uploadcare.com/files/017162da-e83b-46fc-89fc-3a7740db0a82/',
            'mime_type' => 'image/jpg',
            'size' => 390340,
            'type' => 'avatar',
        ]);
    }
}
