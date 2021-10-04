<?php

namespace App\Http\ViewHelpers\Company\HR;

use App\Helpers\DateHelper;
use App\Models\User\Pronoun;
use App\Models\Company\Company;
use App\Models\Company\ECoffee;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\JobOpening;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Company\AskMeAnythingSession;

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
            ->pluck('id');

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

    /**
     * Get the statistics about all the genders used in the company, sorted
     * by the gender used the most.
     *
     * @param Company $company
     * @return array
     */
    public static function genderStats(Company $company): array
    {
        $pronouns = Pronoun::addSelect([
                'number_of_employees' => Employee::selectRaw('count(*)')
                    ->whereColumn('pronoun_id', 'pronouns.id')
                    ->notLocked()
                    ->where('company_id', $company->id),
            ])->get();

        $pronouns = $pronouns->filter(function ($pronoun) {
            return $pronoun->number_of_employees != 0;
        });

        $totalNumberOfEmployees = $company->employees()->notLocked()->count();
        $totalNumberOfEmployeesWithoutPronoun = $company->employees()->notLocked()->whereNull('pronoun_id')->count();

        $pronounCollection = collect();

        // stat about employees without gender
        $pronounCollection->push([
            'id' => 0,
            'label' => trans('account.pronoun_blank'),
            'number_of_employees' => $totalNumberOfEmployeesWithoutPronoun,
            'percent' => (int) round($totalNumberOfEmployeesWithoutPronoun * 100 / $totalNumberOfEmployees, 0),
        ]);

        // stats about genders
        foreach ($pronouns as $pronoun) {
            $pronounCollection->push([
                'id' => $pronoun->id,
                'label' => trans($pronoun->translation_key),
                'number_of_employees' => (int) $pronoun->number_of_employees,
                'percent' => (int) round($pronoun->number_of_employees * 100 / $totalNumberOfEmployees, 0),
            ]);
        }

        return $pronounCollection->sortByDesc('percent')->values()->all();
    }

    /**
     * Get the statistics about all the positions in the company.
     *
     * @param Company $company
     * @return array
     */
    public static function positions(Company $company): array
    {
        $positions = Position::addSelect([
            'number_of_employees' => Employee::selectRaw('count(*)')
                ->whereColumn('position_id', 'positions.id')
                ->notLocked()
                ->where('company_id', $company->id),
        ])->get();

        $positions = $positions->filter(function ($position) {
            return $position->number_of_employees != 0;
        });

        $totalNumberOfEmployees = $company->employees()->notLocked()->count();

        $positionsCollection = collect();
        foreach ($positions as $position) {
            $positionsCollection->push([
                'id' => $position->id,
                'title' => $position->title,
                'number_of_employees' => (int) $position->number_of_employees,
                'percent' => (int) round($position->number_of_employees * 100 / $totalNumberOfEmployees, 0),
                'url' => route('hr.positions.show', [
                    'company' => $company->id,
                    'position' => $position->id,
                ]),
            ]);
        }

        return $positionsCollection->sortByDesc('number_of_employees')->values()->all();
    }

    /**
     * Get the upcoming Ask My Anything Session in the company.
     *
     * @param Company $company
     * @return array
     */
    public static function askMeAnythingUpcomingSession(Company $company): array
    {
        $upcomingSession = AskMeAnythingSession::where('company_id', $company->id)
            ->where('active', true)
            ->with('questions')
            ->first();

        $session = $upcomingSession ? [
            'happened_at' => DateHelper::formatFullDate($upcomingSession->happened_at),
            'theme' => $upcomingSession->theme,
            'questions_count' => $upcomingSession->questions->count(),
            'url' => route('hr.ama.show', [
                'company' => $company->id,
                'session' => $upcomingSession->id,
            ]),
        ] : null;

        return [
            'session' => $session,
            'url_view_all' => route('hr.ama.index', [
                'company' => $company->id,
            ]),
        ];
    }

    /**
     * Get the information about open job openings.
     *
     * @param Company $company
     * @return array
     */
    public static function openedJobOpenings(Company $company): array
    {
        $openings = JobOpening::where('company_id', $company->id)
            ->where('active', true)
            ->where('fulfilled', false)
            ->with('candidates')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        $numberOfJobOpeningsTotal = JobOpening::where('company_id', $company->id)
            ->where('active', true)
            ->where('fulfilled', false)
            ->count();

        $openingsCollection = collect([]);
        foreach ($openings as $opening) {
            $team = $opening->team;

            $openingsCollection->push([
                'id' => $opening->id,
                'title' => $opening->title,
                'reference_number' => $opening->reference_number,
                'team' => $team ? [
                    'id' => $team->id,
                    'name' => $team->name,
                    'url' => route('team.show', [
                        'company' => $company,
                        'team' => $team,
                    ]),
                ] : null,
                'url' => route('jobs.company.show.incognito', [
                    'company' => $company->slug,
                    'job' => $opening->slug,
                ]),
            ]);
        }

        return [
            'jobOpenings' => $openingsCollection,
            'count' => $numberOfJobOpeningsTotal,
            'url_view_all' => route('jobs.company.index', [
                'company' => $company->slug,
            ]),
        ];
    }
}
