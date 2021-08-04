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
            $company = $jobOpening->company;
            $openJobOpenings = $company->jobOpenings()
                ->where('active', true)
                ->where('fulfilled', false)
                ->count();

            $companiesCollection->push([
                'id' => (int) $jobOpening->company_id,
                'name' => $company->name,
                'location' => $company->location,
                'logo' => $company->logo ? ImageHelper::getImage($company->logo, 300, 300) : null,
                'count' => $openJobOpenings,
                'url' => route('jobs.company.index', [
                    'company' => $company->slug,
                ]),
            ]);
        }

        return $companiesCollection->sortBy('name')->values()->all();
    }
}
