<?php

namespace App\Services\Company\Adminland\CompanyPTOPolicy;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\CompanyCalendar;
use App\Models\Company\CompanyPTOPolicy;
use App\Exceptions\CompanyPTOPolicyAlreadyExistException;

class CreateCompanyPTOPolicy extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'year' => 'required|date_format:Y',
            'default_amount_of_allowed_holidays' => 'required|integer',
            'default_amount_of_sick_days' => 'required|integer',
            'default_amount_of_pto_days' => 'required|integer',
        ];
    }

    /**
     * Create a company PTO policy.
     *
     * @param array $data
     *
     * @return CompanyPTOPolicy
     */
    public function execute(array $data): CompanyPTOPolicy
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        // check if there is a policy for the given year already
        $existingPolicy = CompanyPTOPolicy::where('company_id', $data['company_id'])
            ->where('year', $data['year'])
            ->first();

        if ($existingPolicy) {
            // that's bad.
            throw new CompanyPTOPolicyAlreadyExistException();
        }

        $ptoPolicy = CompanyPTOPolicy::create([
            'company_id' => $data['company_id'],
            'year' => $data['year'],
            'total_worked_days' => 261,
            'default_amount_of_allowed_holidays' => $data['default_amount_of_allowed_holidays'],
            'default_amount_of_sick_days' => $data['default_amount_of_sick_days'],
            'default_amount_of_pto_days' => $data['default_amount_of_pto_days'],
        ]);

        // fix the number of worked days to be sure
        $offDays = $this->populateCalendar($data, $ptoPolicy);
        $numberOfWorkedDays = DateHelper::getNumberOfDaysInYear(Carbon::now()) - $offDays;

        // update
        CompanyPTOPolicy::where('id', $ptoPolicy->id)->update([
            'total_worked_days' => $numberOfWorkedDays,
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'company_pto_policy_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'company_pto_policy_id' => $ptoPolicy->id,
                'company_pto_policy_year' => $ptoPolicy->year,
            ]),
        ])->onQueue('low');

        return $ptoPolicy;
    }

    /**
     * Populate the calendar for the entire year with the days. By default it
     * will contain all the days. Weekends will be marked as non-working days.
     * Right after, employers will be able to identify which days are holidays
     * and therefore considered as being off.
     *
     * @param array            $data
     * @param CompanyPTOPolicy $ptoPolicy
     *
     * @return int
     */
    private function populateCalendar(array $data, CompanyPTOPolicy $ptoPolicy): int
    {
        $day = Carbon::create($data['year']);
        $numberOfDaysOff = 0;

        for ($counter = 1; $counter <= DateHelper::getNumberOfDaysInYear($day); $counter++) {
            $isWorked = true;
            if ($day->isSaturday() || $day->isSunday()) {
                $isWorked = false;
                $numberOfDaysOff++;
            }

            CompanyCalendar::create([
                'company_pto_policy_id' => $ptoPolicy->id,
                'day' => $day->format('Y-m-d'),
                'day_of_week' => $day->dayOfWeek,
                'day_of_year' => $day->dayOfYear,
                'is_worked' => $isWorked,
            ]);

            $day->addDay();
        }

        return $numberOfDaysOff;
    }
}
