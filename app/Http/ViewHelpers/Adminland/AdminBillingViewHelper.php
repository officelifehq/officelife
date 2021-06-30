<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\File;
use App\Models\Company\Company;
use Illuminate\Support\Facades\DB;

class AdminBillingViewHelper
{
    /**
     * Get all the information about the account usage.
     *
     * @param Company $company
     * @return array|null
     */
    public static function index(Company $company): ?array
    {
        $accountUsageCollection = collect();
        $usages = $company->
        $administrators = $company->employees()
            ->notLocked()
            ->where('permission_level', 100)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($administrators as $employee) {
            $administratorsCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 22),
                'url_view' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        // creation date of the account
        $creationDate = DateHelper::formatShortDateWithTime($company->created_at, $loggedEmployee->timezone);

        // total file sizes
        $totalSize = DB::table('files')->where('company_id', $company->id)
            ->sum('size');

        // logo
        $logo = $company->logo ? ImageHelper::getImage($company->logo, 300, 300) : null;

        // founded date
        $foundedDate = $company->founded_at ? $company->founded_at->year : null;

        // code to invite employees
        $invitationCode = $company->code_to_join_company;

        return [
            'id' => $company->id,
            'name' => $name,
            'administrators' => $administratorsCollection,
            'creation_date' => $creationDate,
            'currency' => $company->currency,
            'total_size' => round($totalSize / 1000, 4),
            'logo' => $logo,
            'uploadcare_public_key' => config('officelife.uploadcare_public_key'),
            'founded_at' => $foundedDate,
            'invitation_code' => $invitationCode,
        ];
    }
}
