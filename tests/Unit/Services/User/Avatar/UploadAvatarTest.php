<?php

namespace Tests\Unit\Services\User\Avatar;

use Tests\TestCase;
use App\Models\User\Avatar;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use App\Services\User\Avatar\UploadAvatar;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploadAvatarTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_uploads_an_avatar_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_uploads_an_avatar_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_uploads_an_avatar_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function it_cant_upload_an_avatar_as_normal_user_for_another_user(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $michael = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $michael->company_id,
        ];

        $this->expectException(ValidationException::class);
        (new UploadAvatar)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        Queue::fake();
        Storage::fake('avatars');

        // create one other active avatar - this one should be marked inactive
        $otherAvatar = Avatar::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'active' => true,
        ]);

        $file = UploadedFile::fake()->image('image.png');

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'photo' => $file,
        ];

        $avatar = (new UploadAvatar)->execute($request);

        $this->assertDatabaseHas('avatars', [
            'id' => $avatar->id,
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'original_filename' => 'image.png',
            'extension' => 'png',
            'size' => 91,
            'active' => true,
        ]);

        $this->assertDatabaseHas('avatars', [
            'id' => $otherAvatar->id,
            'company_id' => $michael->company_id,
            'employee_id' => $dwight->id,
            'active' => false,
        ]);

        $this->assertInstanceOf(
            Avatar::class,
            $avatar
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'employee_avatar_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'employee_avatar_set' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([]);
        });
    }
}
