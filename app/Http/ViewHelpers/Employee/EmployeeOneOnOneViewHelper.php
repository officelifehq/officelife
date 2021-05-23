<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

class EmployeeOneOnOneViewHelper
{
    /**
     * Array containing the main statistics about the one on ones of this employee.
     *
     * @param Collection $entries
     * @return array
     */
    public static function stats(Collection $entries): array
    {
        $now = Carbon::now();
        $entriesLast365Days = $entries->filter(function ($entry) use ($now) {
            return $entry->happened_at > $now->copy()->subYear();
        });

        // now calculating the average number of days between one on ones
        $previousEntry = $now;
        $average = collect([]);
        foreach ($entriesLast365Days as $entry) {
            $numberOfDays = $previousEntry->diffInDays($entry->happened_at);
            $average->push($numberOfDays);
            $previousEntry = $entry->happened_at;
        }

        $average = $average->avg();

        return [
            'numberOfOccurrencesThisYear' => $entriesLast365Days->count(),
            'averageTimeBetween' => round($average),
        ];
    }

    /**
     * Array containing all the one on ones done.
     *
     * @param Collection $oneOnOnes
     * @param Employee $employee
     * @param Employee $loggedEmployee
     * @return SupportCollection
     */
    public static function list(Collection $oneOnOnes, Employee $employee, Employee $loggedEmployee): SupportCollection
    {
        $company = $employee->company;

        $collection = collect([]);
        foreach ($oneOnOnes as $oneOnOne) {
            $collection->push([
                'id' => $oneOnOne->id,
                'happened_at' => DateHelper::formatDate($oneOnOne->happened_at, $loggedEmployee->timezone),
                'number_of_talking_points' => $oneOnOne->talkingPoints->count(),
                'number_of_action_items' => $oneOnOne->actionItems->count(),
                'number_of_notes' => $oneOnOne->notes->count(),
                'manager' => [
                    'id' => $oneOnOne->manager->id,
                    'name' => $oneOnOne->manager->name,
                    'avatar' => ImageHelper::getAvatar($oneOnOne->manager, 22),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $oneOnOne->manager,
                    ]),
                ],
                'url' => route('employees.show.performance.oneonones.show', [
                    'company' => $company,
                    'employee' => $employee,
                    'oneonone' => $oneOnOne,
                ]),
            ]);
        }

        return $collection;
    }

    /**
     * Get the details of a one on one.
     *
     * @param OneOnOneEntry $entry
     * @param Employee $employee
     * @return array
     */
    public static function details(OneOnOneEntry $entry, Employee $employee): array
    {
        // get previous and next entries, if they exist
        $previousEntry = OneOnOneEntry::where('id', '<', $entry->id)
            ->where('manager_id', $entry->manager->id)
            ->where('employee_id', $entry->employee->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextEntry = OneOnOneEntry::where('id', '>', $entry->id)
            ->where('manager_id', $entry->manager->id)
            ->where('employee_id', $entry->employee->id)
            ->orderBy('id', 'asc')
            ->first();

        $items = $entry->actionItems;
        $actionItems = collect([]);
        foreach ($items as $actionItem) {
            $actionItems->push([
                'id' => $actionItem->id,
                'description' => $actionItem->description,
                'checked' => $actionItem->checked,
            ]);
        }

        $items = $entry->talkingPoints;
        $talkingPoints = collect([]);
        foreach ($items as $talkingPoint) {
            $talkingPoints->push([
                'id' => $talkingPoint->id,
                'description' => $talkingPoint->description,
                'checked' => $talkingPoint->checked,
            ]);
        }

        $items = $entry->notes;
        $notes = collect([]);
        foreach ($items as $note) {
            $notes->push([
                'id' => $note->id,
                'note' => $note->note,
            ]);
        }

        $company = $entry->employee->company;

        $array = [
            'id' => $entry->id,
            'happened_at' => DateHelper::formatDate($entry->happened_at, $employee->timezone),
            'happened' => $entry->happened,
            'employee' => [
                'id' => $entry->employee->id,
                'name' => $entry->employee->name,
                'avatar' => ImageHelper::getAvatar($entry->employee, 22),
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $entry->employee,
                ]),
            ],
            'manager' => [
                'id' => $entry->manager->id,
                'name' => $entry->manager->name,
                'avatar' => ImageHelper::getAvatar($entry->manager, 22),
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $entry->manager,
                ]),
            ],
            'talking_points' => $talkingPoints,
            'action_items' => $actionItems,
            'notes' => $notes,
            'previous_entry' => $previousEntry ? [
                'happened_at' => DateHelper::formatDate($previousEntry->happened_at, $employee->timezone),
                'url' => route('employees.show.performance.oneonones.show', [
                    'company' => $company,
                    'employee' => $entry->employee,
                    'oneonone' => $previousEntry,
                ]),
            ] : null,
            'next_entry' => $nextEntry ? [
                'happened_at' => DateHelper::formatDate($nextEntry->happened_at, $employee->timezone),
                'url' => route('employees.show.performance.oneonones.show', [
                    'company' => $company,
                    'employee' => $entry->employee,
                    'oneonone' => $nextEntry,
                ]),
            ] : null,
        ];

        return $array;
    }
}
