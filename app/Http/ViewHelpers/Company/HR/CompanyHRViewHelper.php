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
            ->selectRaw('count(case when happened = true then 1 end) as happened')
            ->groupBy('e_coffees.id')
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
            ->selectRaw('count(case when happened = true then 1 end) as happened')
            ->groupBy('e_coffees.id')
            ->first();

        // get all ecoffees id
        $allMatchesInCompany = DB::table('e_coffees')
            ->where('company_id', $company->id)
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        // list all matches for each ecoffee sessions
        $globalPercentageOfParticipation = DB::table('e_coffee_matches')
            ->selectRaw('count(*) as total')
            ->selectRaw('e_coffee_id as id')
            ->selectRaw('count(case when happened = true then 1 end) as happened')
            ->whereIn('e_coffee_id', $allMatchesInCompany)
            ->groupBy('e_coffee_id')
            ->get();

        $statsForEachMatch = collect([]);
        foreach ($globalPercentageOfParticipation as $stat) {
            $statsForEachMatch->push(
                round($stat->happened * 100 / $stat->total)
            );
        }

        $averageTotalSessions = round($statsForEachMatch->avg());

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
            'average_total_sessions' => $averageTotalSessions,
            'number_of_sessions' => $numberOfSessions,
        ];
    }
}
