<?php

namespace App\Http\ViewHelpers\Jobs;

use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use App\Models\Company\JobOpening;

class JobsViewHelper
{
    /**
     * Get all the companies in the instance.
     *
     * @return array
     */
    public static function index(): array
    {
        // for sure we can find a simpler way to get all the open job openings
        // for each company in only one query
        $companyWithJobOpenings = JobOpening::where('fulfilled', false)
            ->where('active', true)
            ->with('company')
            ->get()
            ->unique('company_id')
            ->values();

        $companiesCollection = collect();
        foreach ($companyWithJobOpenings as $jobOpening) {
            $companiesCollection->push([
                'id' => (int) $jobOpening->company_id,
                'name' => $jobOpening->company->name,
                'location' => $jobOpening->company->location,
                'logo' => $jobOpening->company->logo ? ImageHelper::getImage($jobOpening->company->logo, 300, 300) : null,
                'count' => $jobOpening->company->jobOpenings()->count(),
                'url' => route('jobs.company.index', [
                    'company' => $jobOpening->company->slug,
                ]),
            ]);
        }

        return $companiesCollection->sortBy('name')->values()->all();
    }
}
