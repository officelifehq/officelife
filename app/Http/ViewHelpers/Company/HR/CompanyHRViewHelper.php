<?php

namespace App\Http\ViewHelpers\Company\HR;

use App\Models\Company\Company;
use App\Models\Company\ECoffee;
use Illuminate\Support\Facades\DB;

class CompanyHRViewHelper
{
    /**
     * Array containing information about the ecoffees in the company.
     * We use a combination of raw queries and manipulation of collections to
     * avoid hydrating models as using Eloquent here would hydrate way
     *  too many models.
     *
     * @param Company $company
     * @return array|null
     */
    public static function eCoffees(Company $company): ?array
    {
        if (! $company->e_coffee_enabled) {
            return null;
        }

        // get all ecoffees id
        $allMatchesInCompany = DB::table('e_coffees')
            ->where('company_id', $company->id)
            ->select('id')
            ->get()
            ->pluck('id')
            ->toArray();

        // list all matches for each ecoffee sessions
        $eCoffeeMatchesParticipated = DB::table('e_coffee_matches')
            ->selectRaw('e_coffee_id as id')
            ->selectRaw('count(happened) as happened')
            ->where('happened', true)
            ->whereIn('e_coffee_id', $allMatchesInCompany)
            ->groupBy('e_coffee_id')
            ->get();

        $totalECoffeeMatches = DB::table('e_coffee_matches')
            ->selectRaw('e_coffee_id as id')
            ->selectRaw('count(*) as total')
            ->whereIn('e_coffee_id', $allMatchesInCompany)
            ->groupBy('e_coffee_id')
            ->get();

        // every information will be in this new collection
        $allData = collect([]);
        foreach ($eCoffeeMatchesParticipated as $item) {
            $matched = $totalECoffeeMatches->filter(function ($match) use ($item) {
                return $match->id == $item->id;
            })->first();

            $allData->push([
                'id' => $item->id,
                'happened' => $item->happened,
                'total' => $matched->total,
            ]);
        }

        // get current session
        $currentECoffeeSession = $allData->sortByDesc('id')->first();

        // before last session
        $beforeLastSession = $allData->sortByDesc('id')->skip(1)->first();

        // calculating the average participation
        $statsForEachMatch = collect([]);
        foreach ($allData as $stat) {
            $statsForEachMatch->push(
                round($stat['happened'] * 100 / $stat['total'])
            );
        }

        $averageTotalSessions = round($statsForEachMatch->avg());

        $numberOfSessions = DB::table('e_coffees')
            ->where('company_id', $company->id)
            ->count();

        return [
            'active_session' => $currentECoffeeSession ? [
                    'total' => $currentECoffeeSession['total'],
                    'happened' => $currentECoffeeSession['happened'],
                    'percent' => round($currentECoffeeSession['happened'] * 100 / $currentECoffeeSession['total']),
                ] : null,
            'last_active_session' => $beforeLastSession ? [
                    'total' => $beforeLastSession['total'],
                    'happened' => $beforeLastSession['happened'],
                    'percent' => round($beforeLastSession['happened'] * 100 / $beforeLastSession['total']),
                ] : null,
            'average_total_sessions' => $averageTotalSessions,
            'number_of_sessions' => $numberOfSessions,
        ];
    }
}
