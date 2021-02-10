<?php

namespace App\Http\ViewHelpers\Company\HR;

use App\Models\Company\Company;
use App\Models\Company\ECoffee;
use Illuminate\Support\Facades\DB;

class CompanyHRViewHelper
{
    /**
     * Array containing information about the ecoffees in the company.
     * We use only raw queries to avoid hydrating models as using Eloquent here
     * would hydrate way too many models.
     *
     * @param Company $company
     * @return array|null
     */
    public static function eCoffees(Company $company): ?array
    {
        if (! $company->e_coffee_enabled) {
            return null;
        }

        // get current active session
        $activeECoffeeSession = DB::table('e_coffee_matches')
            ->join('e_coffees', 'e_coffee_matches.e_coffee_id', '=', 'e_coffees.id')
            ->where('e_coffees.company_id', $company->id)
            ->where('e_coffees.active', true)
            ->selectRaw('count(*) as total')
            ->selectRaw('count(happened or null) as happened')
            ->first();

        // get the previously active session
        $previouslyActiveEcoffee = ECoffee::where('company_id', $company->id)
            ->orderBy('id', 'desc')
            ->where('active', false)
            ->take(1)
            ->first();

        $previouslyActiveSession = DB::table('e_coffee_matches')
            ->join('e_coffees', 'e_coffee_matches.e_coffee_id', '=', 'e_coffees.id')
            ->where('e_coffees.id', $previouslyActiveEcoffee->id)
            ->orderBy('e_coffees.id', 'desc')
            ->selectRaw('count(*) as total')
            ->selectRaw('count(happened or null) as happened')
            ->first();

        // get all statistics
        $globalPercentageOfParticipation = DB::table('e_coffee_matches')
            ->join('e_coffees', 'e_coffee_matches.e_coffee_id', '=', 'e_coffees.id')
            ->where('e_coffees.company_id', $company->id)
            ->where('e_coffees.active', false)
            ->orderBy('e_coffees.id', 'desc')
            ->selectRaw('count(*) as total')
            ->selectRaw('count(happened or null) as happened')
            ->first();

        $numberOfSessions = DB::table('e_coffees')
            ->where('company_id', $company->id)
            ->count();

        return [
            'active_session' => $activeECoffeeSession ? [
                    'total' => $activeECoffeeSession->total,
                    'happened' => $activeECoffeeSession->happened,
                    'percent' => round($activeECoffeeSession->happened * 100 / $activeECoffeeSession->total),
                ] : null,
            'last_active_session' => $previouslyActiveSession ? [
                    'total' => $previouslyActiveSession->total,
                    'happened' => $previouslyActiveSession->happened,
                    'percent' => round($previouslyActiveSession->happened * 100 / $previouslyActiveSession->total),
                ] : null,
            'total_previous_sessions' => $globalPercentageOfParticipation ? [
                    'total' => $globalPercentageOfParticipation->total,
                    'happened' => $globalPercentageOfParticipation->happened,
                    'percent' => round($globalPercentageOfParticipation->happened * 100 / $globalPercentageOfParticipation->total),
                ] : null,
            'number_of_sessions' => $numberOfSessions,
        ];
    }
}
