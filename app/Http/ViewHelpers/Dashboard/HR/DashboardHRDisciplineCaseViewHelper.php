<?php

namespace App\Http\ViewHelpers\Dashboard\HR;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use App\Models\Company\DisciplineCase;

class DashboardHRDisciplineCaseViewHelper
{
    /**
     * Get the information about all the discipline cases.
     *
     * @param Company $company
     * @return array|null
     */
    public static function index(Company $company): ?array
    {
        $openCases = DisciplineCase::where('company_id', $company->id)
            ->where('active', true)
            ->orderBy('created_at', 'desc')
            ->with('author')
            ->with('employee')
            ->get();

        $openCasesCollection = collect([]);
        foreach ($openCases as $openCase) {
            $openCasesCollection->push(
                self::dto($company, $openCase)
            );
        }

        $closedCasesCount = DisciplineCase::where('company_id', $company->id)
            ->where('active', false)
            ->count();

        return [
            'open_cases' => $openCasesCollection,
            'open_cases_count' => $openCasesCollection->count(),
            'closed_cases_count' => $closedCasesCount,
            'url' => [
                'open' => route('dashboard.hr.disciplinecase.index', [
                    'company' => $company,
                ]),
                'closed' => route('dashboard.hr.disciplinecase.index.closed', [
                    'company' => $company,
                ]),
                'search' => route('dashboard.hr.disciplinecase.search.employees', [
                    'company' => $company,
                ]),
                'store' => route('dashboard.hr.disciplinecase.store', [
                    'company' => $company,
                ]),
            ],
        ];
    }

    public static function dto(Company $company, DisciplineCase $case): array
    {
        return [
            'id' => $case->id,
            'opened_at' => DateHelper::formatDate($case->created_at),
            'author' => $case->author ? [
                'id' => $case->author->id,
                'name' => $case->author->name,
                'avatar' => ImageHelper::getAvatar($case->author, 40),
                'position' => (! $case->author->position) ? null : $case->author->position->title,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $case->author,
                ]),
            ] : [
                'name' => $case->author_name,
            ],
            'employee' => [
                'id' => $case->employee->id,
                'name' => $case->employee->name,
                'avatar' => ImageHelper::getAvatar($case->employee, 40),
                'position' => (! $case->employee->position) ? null : $case->employee->position->title,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $case->employee,
                ]),
            ],
            'url' => [
                'show' => route('dashboard.hr.disciplinecase.show', [
                    'company' => $company,
                    'case' => $case,
                ]),
            ],
        ];
    }

    /**
     * Returns the potential employees who can be assigned a case.
     *
     * @param Company $company
     * @param string $criteria
     * @return Collection
     */
    public static function potentialEmployees(Company $company, string $criteria): Collection
    {
        $potentialEmployees = $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%' . $criteria . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $criteria . '%')
                    ->orWhere('email', 'LIKE', '%' . $criteria . '%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get();

        $potentialEmployeesCollection = collect([]);
        foreach ($potentialEmployees as $potential) {
            $potentialEmployeesCollection->push([
                'id' => $potential->id,
                'name' => $potential->name,
                'avatar' => ImageHelper::getAvatar($potential, 64),
            ]);
        }

        return $potentialEmployeesCollection;
    }

    /**
     * Show the given case.
     *
     * @param Company $company
     * @param DisciplineCase $case
     */
    public static function show(Company $company, DisciplineCase $case)
    {
        $events = $case->events()
            ->with('author')->with('files')
            ->orderBy('happened_at', 'desc')
            ->get();

        $eventsCollection = collect();
        foreach ($events as $event) {
            $eventsCollection->push(
                DashboardHRDisciplineEventViewHelper::dto($company, $case, $event)
            );
        }

        return [
            'events' => $eventsCollection,
            'opened_at' => DateHelper::formatDate($case->created_at),
            'author' => [
                'id' => $case->author->id,
                'name' => $case->author->name,
                'avatar' => ImageHelper::getAvatar($case->author, 40),
                'position' => (! $case->author->position) ? null : $case->author->position->title,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $case->author,
                ]),
            ],
            'employee' => [
                'id' => $case->employee->id,
                'name' => $case->employee->name,
                'avatar' => ImageHelper::getAvatar($case->employee, 40),
                'position' => (! $case->employee->position) ? null : $case->employee->position->title,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $case->employee,
                ]),
            ],
            'url' => [
                'events' => [
                    'store' => route('dashboard.hr.disciplineevent.store', [
                        'company' => $company,
                        'case' => $case,
                    ]),
                ],
            ],
        ];
    }
}
