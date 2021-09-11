<?php

namespace App\Http\ViewHelpers\Company\HR;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use App\Models\Company\AskMeAnythingSession;

class CompanyHRAskMeAnythingViewHelper
{
    /**
     * Get all the sessions in the company.
     *
     * @param Company $company
     * @return Collection
     */
    public static function index(Company $company): Collection
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
            ]);
        }

        return $sessionsCollection;
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
     * @return array
     */
    public static function show(Company $company, AskMeAnythingSession $session): array
    {
        $questionsCollection = collect();
        foreach ($session->questions as $question) {
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
            ]);
        }

        return [
            'id' => $session->id,
            'active' => $session->active,
            'theme' => $session->theme,
            'happened_at' => DateHelper::formatDate($session->happened_at),
            'questions' => $questionsCollection,
        ];
    }
}
