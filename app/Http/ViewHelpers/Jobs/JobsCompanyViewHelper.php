<?php

namespace App\Http\ViewHelpers\Jobs;

use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\JobOpening;

class JobsCompanyViewHelper
{
    /**
     * Get all the active job openings for a given company.
     *
     * @param Company $company
     * @return array
     */
    public static function index(Company $company): array
    {
        $jobOpenings = JobOpening::where('fulfilled', false)
            ->where('active', true)
            ->where('company_id', $company->id)
            ->with('team')
            ->get();

        $jobOpeningsCollection = collect();
        foreach ($jobOpenings as $jobOpening) {
            $jobOpeningsCollection->push([
                'id' => $jobOpening->id,
                'reference_number' => $jobOpening->reference_number,
                'title' => $jobOpening->title,
                'team' => $jobOpening->team ? [
                    'name' => $jobOpening->team->name,
                ] : null,
            ]);
        }

        $company = [
            'id' => (int) $jobOpening->company_id,
            'name' => $jobOpening->company->name,
            'location' => $jobOpening->company->location,
            'logo' => $jobOpening->company->logo ? ImageHelper::getImage($jobOpening->company->logo, 300, 300) : null,
        ];

        return [
            'company' => $company,
            'job_openings' => $jobOpeningsCollection,
        ];
    }

    /**
     * Get the information about a job opening.
     *
     * @param Company $company
     * @param JobOpening $jobOpening
     */
    public static function show(Company $company, JobOpening $jobOpening)
    {
        $jobOpening = [
            'id' => $jobOpening->id,
            'reference_number' => $jobOpening->reference_number,
            'title' => $jobOpening->title,
            'description' => StringHelper::parse($jobOpening->description),
            'team' => $jobOpening->team ? [
                'name' => $jobOpening->team->name,
            ] : null,
        ];

        $company = [
            'id' => $company->id,
            'name' => $company->name,
            'location' => $company->location,
            'logo' => $company->logo ? ImageHelper::getImage($company->logo, 300, 300) : null,
        ];

        return [
            'company' => $company,
            'job_opening' => $jobOpening,
        ];
    }
}
