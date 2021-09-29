<?php

namespace App\Http\ViewHelpers\Company\Group;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Company;
use App\Models\Company\Meeting;
use Illuminate\Support\Collection;

class GroupMeetingsViewHelper
{
    /**
     * Get all the meetings in the group.
     *
     * @param Group $group
     * @return array
     */
    public static function index(Group $group): array
    {
        $meetings = $group->meetings()->orderBy('happened_at', 'desc')->with('employees')->get();

        $meetingsCollection = collect([]);
        foreach ($meetings as $meeting) {
            $members = $meeting->employees()
                ->wherePivot('attended', true)
                ->inRandomOrder()
                ->take(3)
                ->get();

            $totalMembersCount = $meeting->employees()->wherePivot('attended', true)->count();
            $totalMembersCount = $totalMembersCount - $members->count();

            $membersCollection = collect([]);
            foreach ($members as $member) {
                $membersCollection->push([
                    'id' => $member->id,
                    'avatar' => ImageHelper::getAvatar($member, 25),
                    'url' => route('employees.show', [
                        'company' => $group->company_id,
                        'employee' => $member,
                    ]),
                ]);
            }

            $meetingsCollection->push([
                'id' => $meeting->id,
                'happened_at' => trans('group.meeting_index_item_title', ['date' => DateHelper::formatDate($meeting->happened_at)]),
                'url' => route('groups.meetings.show', [
                    'company' => $group->company_id,
                    'group' => $group,
                    'meeting' => $meeting,
                ]),
                'preview_members' => $membersCollection,
                'remaining_members_count' => $totalMembersCount,
            ]);
        }

        return [
            'meetings' => $meetingsCollection,
            'url_new' => route('groups.meetings.new', [
                'company' => $group->company_id,
                'group' => $group,
            ]),
        ];
    }

    /**
     * Get information of a specific meeting.
     *
     * @param Meeting $meeting
     * @param Company $company
     * @return array
     */
    public static function show(Meeting $meeting, Company $company): array
    {
        $participants = $meeting->employees()
            ->orderBy('last_name', 'asc')
            ->get();

        $participantsCollection = collect([]);
        $guestsCollection = collect([]);
        foreach ($participants as $employee) {
            if ((bool) $employee->pivot->was_a_guest) {
                $guestsCollection->push([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 23),
                    'attended' => (bool) $employee->pivot->attended,
                    'was_a_guest' => true,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $employee,
                    ]),
                ]);
            } else {
                $participantsCollection->push([
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 23),
                    'attended' => (bool) $employee->pivot->attended,
                    'was_a_guest' => false,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $employee,
                    ]),
                ]);
            }
        }

        return [
            'meeting' => [
                'id' => $meeting->id,
                'happened_at' => DateHelper::formatDate($meeting->happened_at),
                'happened_at_max_year' => Carbon::now()->addYear()->year,
                'happened_at_year' => $meeting->happened_at->year,
                'happened_at_month' => $meeting->happened_at->month,
                'happened_at_day' => $meeting->happened_at->day,
            ],
            'participants' => $participantsCollection,
            'guests' => $guestsCollection,
        ];
    }

    /**
     * Get potential guests of this meeting.
     *
     * @param Meeting $meeting
     * @param Company $company
     * @param string|null $criteria
     * @return Collection
     */
    public static function potentialGuests(Meeting $meeting, Company $company, ?string $criteria): Collection
    {
        $members = $meeting->employees()
            ->select('id')
            ->pluck('id');

        return $company->employees()
            ->select('id', 'first_name', 'last_name')
            ->notLocked()
            ->whereNotIn('id', $members)
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                ];
            });
    }

    /**
     * Get agenda of the meeting.
     *
     * @param Meeting $meeting
     * @param Company $company
     * @return Collection
     */
    public static function agenda(Meeting $meeting, Company $company): Collection
    {
        $items = $meeting->agendaItems()
            ->orderBy('id', 'asc')
            ->with('presenter')
            ->with('decisions')
            ->get();

        $agendaCollection = collect([]);
        foreach ($items as $agendaItem) {
            $presenter = $agendaItem->presenter;

            // decisions
            $decisionsCollection = collect([]);
            foreach ($agendaItem->decisions as $decision) {
                $decisionsCollection->push([
                    'id' => $decision->id,
                    'description' => $decision->description,
                ]);
            }

            // preparing final collection
            $agendaCollection->push([
                'id' => $agendaItem->id,
                'position' => $agendaItem->position,
                'summary' => $agendaItem->summary,
                'description' => $agendaItem->description,
                'presenter' => $presenter ? [
                    'id' => $presenter->id,
                    'name' => $presenter->name,
                    'avatar' => ImageHelper::getAvatar($presenter, 23),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $presenter,
                    ]),
                ] : null,
                'decisions' => $decisionsCollection,
            ]);
        }

        return $agendaCollection;
    }

    public static function potentialPresenters(Meeting $meeting, Company $company): Collection
    {
        $participants = $meeting->employees()->notLocked()->get();

        $employeesCollection = collect([]);
        foreach ($participants as $employee) {
            $employeesCollection->push([
                'value' => $employee->id,
                'label' => $employee->name,
            ]);
        }

        return $employeesCollection;
    }
}
