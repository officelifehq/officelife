<?php

namespace App\Http\ViewHelpers\Dashboard\HR;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\DisciplineCase;
use App\Models\Company\DisciplineEvent;

class DashboardHRDisciplineEventViewHelper
{
    /**
     * Get the information about the discipline event.
     *
     * @param Company $company
     * @param DisciplineCase $case
     * @param DisciplineEvent $event
     * @return array
     */
    public static function dto(Company $company, DisciplineCase $case, DisciplineEvent $event): array
    {
        return [
            'id' => $event->id,
            'happened_at' => DateHelper::formatDate($event->happened_at),
            'description' => StringHelper::parse($event->description),
            'author' => $event->author ? [
                'id' => $event->author->id,
                'name' => $event->author->name,
                'avatar' => ImageHelper::getAvatar($event->author, 40),
                'position' => (! $event->author->position) ? null : $event->author->position->title,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $event->author,
                ]),
            ] : [
                'name' => $event->author_name,
            ],
            'url' => [
                'update' => route('dashboard.hr.disciplineevent.update', [
                    'company' => $company,
                    'case' => $case,
                    'event' => $event,
                ]),
                'delete' => route('dashboard.hr.disciplineevent.destroy', [
                    'company' => $company,
                    'case' => $case,
                    'event' => $event,
                ]),
            ],
        ];
    }
}
