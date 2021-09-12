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
        $sessions = AskMeAnythingSession::where('company_id', $company->id)
            ->with('questions')
            ->orderBy('happened_at', 'desc')
            ->get();

        $sessionsCollection = collect();
        foreach ($sessions as $session) {
            $sessionsCollection->push([
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

        return [
            'sessions' => $sessionsCollection,
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
            ],
        ];
    }
}
