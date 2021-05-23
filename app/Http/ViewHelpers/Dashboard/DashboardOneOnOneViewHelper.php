<?php

namespace App\Http\ViewHelpers\Dashboard;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Employee;
use App\Models\Company\OneOnOneEntry;

class DashboardOneOnOneViewHelper
{
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
                'url' => route('dashboard.oneonones.show', [
                    'company' => $company,
                    'entry' => $previousEntry,
                ]),
            ] : null,
            'next_entry' => $nextEntry ? [
                'happened_at' => DateHelper::formatDate($nextEntry->happened_at, $employee->timezone),
                'url' => route('dashboard.oneonones.show', [
                    'company' => $company,
                    'entry' => $nextEntry,
                ]),
            ] : null,
        ];

        return $array;
    }
}
