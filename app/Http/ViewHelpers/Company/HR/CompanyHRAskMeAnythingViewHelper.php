<?php

namespace App\Http\ViewHelpers\Company\HR;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\AskMeAnythingSession;

class CompanyHRAskMeAnythingViewHelper
{
    /**
     * Get all the sessions in the company.
     *
     * @param Company $company
     * @param Employee $employee
     * @return array
     */
    public static function index(Company $company, Employee $employee): array
    {
        $inactiveSessions = AskMeAnythingSession::where('company_id', $company->id)
            ->where('active', false)
            ->with('questions')
            ->orderBy('happened_at', 'desc')
            ->get();

        $inactiveSessionsCollection = collect();
        foreach ($inactiveSessions as $session) {
            $inactiveSessionsCollection->push([
                'id' => $session->id,
                'active' => $session->active,
                'theme' => $session->theme,
                'happened_at' => DateHelper::formatDate($session->happened_at),
                'questions_count' => $session->questions->count(),
                'url' => route('hr.ama.show', [
                    'company' => $company->id,
                    'session' => $session->id,
                ]),
            ]);
        }

        // get active session
        $activeSession = AskMeAnythingSession::where('company_id', $company->id)
            ->where('active', true)
            ->with('questions')
            ->first();

        $activeSession = $activeSession ? [
            'id' => $activeSession->id,
            'theme' => $activeSession->theme,
            'happened_at' => DateHelper::formatDate($activeSession->happened_at),
            'questions_count' => $activeSession->questions->count(),
            'url' => route('hr.ama.show', [
                'company' => $company->id,
                'session' => $activeSession->id,
            ]),
        ] : null;

        return [
            'active_session' => $activeSession,
            'inactive_sessions' => $inactiveSessionsCollection,
            'can_create' => $employee->permission_level <= config('officelife.permission_level.hr'),
            'url_new' => route('hr.ama.create', [
                'company' => $company->id,
            ]),
        ];
    }

    /**
     * Get all the data necessary for the create screen.
     *
     * @param Company $company
     * @return array
     */
    public static function new(Company $company): array
    {
        $urlBack = route('hr.ama.index', [
            'company' => $company->id,
        ]);
        $urlSubmit = route('hr.ama.store', [
            'company' => $company->id,
        ]);

        return [
            'url_back' => $urlBack,
            'url_submit' => $urlSubmit,
            'year' => Carbon::now()->year,
            'month' => Carbon::now()->month,
            'day' => Carbon::now()->day,
        ];
    }

    /**
     * Get the details of a session.
     *
     * @param Company $company
     * @param AskMeAnythingSession $session
     * @param Employee $employee
     * @param bool $showAnswered
     * @return array
     */
    public static function show(Company $company, AskMeAnythingSession $session, Employee $employee, bool $showAnswered): array
    {
        $questions = $session->questions()
            ->where('answered', $showAnswered)
            ->get();

        $questionsCollection = collect();
        foreach ($questions as $question) {
            $author = $question->employee;

            $questionsCollection->push([
                'id' => $question->id,
                'question' => $question->question,
                'answered' => $question->answered,
                'author' => $author && ! $question->anonymous ? [
                    'id' => $author->id,
                    'name' => $author->name,
                    'avatar' => ImageHelper::getAvatar($author, 22),
                    'position' => (! $author->position) ? null : [
                        'id' => $author->position->id,
                        'title' => $author->position->title,
                    ],
                    'url_view' => route('employees.show', [
                        'company' => $company,
                        'employee' => $author,
                    ]),
                ] : null,
                'url_toggle' => route('hr.ama.question.toggle', [
                    'company' => $company->id,
                    'session' => $session->id,
                    'question' => $question->id,
                ]),
            ]);
        }

        return [
            'id' => $session->id,
            'active' => $session->active,
            'theme' => $session->theme,
            'happened_at' => DateHelper::formatDate($session->happened_at),
            'questions' => $questionsCollection,
            'permissions' => [
                'can_mark_answered' => $employee->permission_level <= config('officelife.permission_level.hr'),
                'can_edit' => $employee->permission_level <= config('officelife.permission_level.hr'),
            ],
            'url' => [
                'unanswered_tab' => route('hr.ama.show', [
                    'company' => $company->id,
                    'session' => $session->id,
                ]),
                'answered_tab' => route('hr.ama.show.answered', [
                    'company' => $company->id,
                    'session' => $session->id,
                ]),
                'edit' => route('hr.ama.edit', [
                    'company' => $company->id,
                    'session' => $session->id,
                ]),
                'delete' => route('hr.ama.delete', [
                    'company' => $company->id,
                    'session' => $session->id,
                ]),
                'toggle' => route('hr.ama.toggle', [
                    'company' => $company->id,
                    'session' => $session->id,
                ]),
            ],
        ];
    }

    /**
     * Get the information needed to edit a session.
     *
     * @param Company $company
     * @param AskMeAnythingSession $session
     * @return array
     */
    public static function edit(Company $company, AskMeAnythingSession $session): array
    {
        return [
            'id' => $session->id,
            'theme' => $session->theme,
            'happened_at_year' => $session->happened_at->year,
            'happened_at_month' => $session->happened_at->month,
            'happened_at_day' => $session->happened_at->day,
            'url' => [
                'update' => route('hr.ama.update', [
                    'company' => $company->id,
                    'session' => $session->id,
                ]),
                'back' => route('hr.ama.show', [
                    'company' => $company->id,
                    'session' => $session->id,
                ]),
            ],
        ];
    }

    /**
     * Get the information needed to delete a session.
     *
     * @param Company $company
     * @param AskMeAnythingSession $session
     * @return array
     */
    public static function delete(Company $company, AskMeAnythingSession $session): array
    {
        return [
            'id' => $session->id,
            'url' => [
                'destroy' => route('hr.ama.destroy', [
                    'company' => $company->id,
                    'session' => $session->id,
                ]),
                'back' => route('hr.ama.show', [
                    'company' => $company->id,
                    'session' => $session->id,
                ]),
            ],
        ];
    }
}
