<?php

namespace Tests\Unit\Services\Company\Employee\RateYourManager;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\RateYourManagerAnswer;
use App\Models\Company\RateYourManagerSurvey;
use Illuminate\Validation\ValidationException;
use App\Exceptions\NotEnoughPermissionException;
use App\Exceptions\SurveyNotActiveAnymoreException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\RateYourManager\RateYourManager;

class RateYourManagerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_rates_a_manager(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $survey = factory(RateYourManagerSurvey::class)->create([
            'manager_id' => $michael->id,
            'active' => true,
        ]);
        $answer = factory(RateYourManagerAnswer::class)->create([
            'employee_id' => $dwight->id,
            'rate_your_manager_survey_id' => $survey->id,
        ]);
        $this->executeService($michael, $dwight, $answer);
    }

    /** @test */
    public function normal_user_cant_execute_the_service_against_another_user(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $survey = factory(RateYourManagerSurvey::class)->create([
            'manager_id' => $michael->id,
            'active' => true,
        ]);
        $answer = factory(RateYourManagerAnswer::class)->create([
            'employee_id' => $dwight->id,
            'rate_your_manager_survey_id' => $survey->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $michael, $answer);
    }

    /** @test */
    public function it_fails_if_the_survey_doesnt_belong_to_the_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $answer = factory(RateYourManagerAnswer::class)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $dwight, $answer);
    }

    /** @test */
    public function it_fails_if_the_survey_is_not_active_anymore(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $survey = factory(RateYourManagerSurvey::class)->create([
            'manager_id' => $michael->id,
            'active' => false,
        ]);
        $answer = factory(RateYourManagerAnswer::class)->create([
            'employee_id' => $dwight->id,
            'rate_your_manager_survey_id' => $survey->id,
        ]);

        $this->expectException(SurveyNotActiveAnymoreException::class);
        $this->executeService($michael, $dwight, $answer);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'employee',
        ];

        $this->expectException(ValidationException::class);
        (new RateYourManager)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_employee_is_not_in_the_authors_company(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createEmployee();
        $survey = factory(RateYourManagerSurvey::class)->create([
            'manager_id' => $michael->id,
            'active' => true,
        ]);
        $answer = factory(RateYourManagerAnswer::class)->create([
            'employee_id' => $dwight->id,
            'rate_your_manager_survey_id' => $survey->id,
        ]);

        $this->expectException(NotEnoughPermissionException::class);
        $this->executeService($michael, $michael, $answer);
    }

    private function executeService(Employee $manager, Employee $employee, RateYourManagerAnswer $answer): void
    {
        Queue::fake();

        $request = [
            'company_id' => $manager->company_id,
            'author_id' => $employee->id,
            'answer_id' => $answer->id,
            'rating' => RateYourManagerAnswer::BAD,
        ];

        $answer = (new RateYourManager)->execute($request);

        $this->assertDatabaseHas('rate_your_manager_answers', [
            'id' => $answer->id,
            'active' => false,
            'rating' => RateYourManagerAnswer::BAD,
        ]);

        $this->assertInstanceOf(
            RateYourManagerAnswer::class,
            $answer
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($manager, $employee) {
            return $job->auditLog['action'] === 'rate_your_manager_survey_answered' &&
                $job->auditLog['author_id'] === $employee->id &&
                $job->auditLog['objects'] === json_encode([
                    'manager_id' => $manager->id,
                    'manager_name' => $manager->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($manager, $employee) {
            return $job->auditLog['action'] === 'rate_your_manager_survey_answered' &&
                $job->auditLog['author_id'] === $employee->id &&
                $job->auditLog['objects'] === json_encode([
                    'manager_id' => $manager->id,
                    'manager_name' => $manager->name,
                ]);
        });
    }
}
