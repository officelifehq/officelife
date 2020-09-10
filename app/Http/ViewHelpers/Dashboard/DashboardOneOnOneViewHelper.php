<?php

namespace App\Http\ViewHelpers\Dashboard;

use App\Helpers\DateHelper;
use App\Models\Company\OneOnOneEntry;

class DashboardOneOnOneViewHelper
{
    /**
     * Get the details of a one on one.
     *
     * @var OneOnOneEntry
     * @return array
     */
    public static function details(OneOnOneEntry $entry): array
    {
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

        $array = [
            'id' => $entry->id,
            'happened_at' => DateHelper::formatDate($entry->happened_at),
            'employee' => [
                'id' => $entry->employee->id,
                'name' => $entry->employee->name,
                'avatar' => $entry->employee->avatar,
                'url' => route('employees.show', [
                    'company' => $entry->employee->company,
                    'employee' => $entry->employee,
                ]),
            ],
            'manager' => [
                'id' => $entry->manager->id,
                'name' => $entry->manager->name,
                'avatar' => $entry->manager->avatar,
                'url' => route('employees.show', [
                    'company' => $entry->employee->company,
                    'employee' => $entry->manager,
                ]),
            ],
            'talking_points' => $talkingPoints,
            'action_items' => $actionItems,
            'notes' => $notes,
        ];

        return $array;
    }
}
