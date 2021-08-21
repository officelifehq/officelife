<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\StringHelper;
use App\Helpers\InstanceHelper;
use App\Models\Company\Candidate;
use Illuminate\Http\JsonResponse;
use App\Models\Company\JobOpening;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\CandidateStage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\ProcessCandidateStage;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRCandidatesViewHelper;
use App\Services\Company\Adminland\JobOpening\CreateCandidateStageNote;
use App\Services\Company\Adminland\JobOpening\UpdateCandidateStageNote;
use App\Services\Company\Adminland\JobOpening\DestroyCandidateStageNote;
use App\Services\Company\Adminland\JobOpening\CreateCandidateStageParticipant;
use App\Services\Company\Adminland\JobOpening\DestroyCandidateStageParticipant;

class DashboardHRCandidateController extends Controller
{
    /**
     * Show the detail of a job opening.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $jobOpeningId, int $candidateId): mixed
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($loggedEmployee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        try {
            $jobOpening = JobOpening::where('company_id', $company->id)
                ->with('team')
                ->with('position')
                ->with('sponsors')
                ->findOrFail($jobOpeningId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $candidate = Candidate::where('company_id', $company->id)
                ->findOrFail($candidateId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $jobOpeningInfo = DashboardHRCandidatesViewHelper::jobOpening($company, $jobOpening);
        $candidateInfo = DashboardHRCandidatesViewHelper::candidate($company, $jobOpening, $candidate);
        $otherJobOpenings = DashboardHRCandidatesViewHelper::otherJobOpenings($company, $candidate, $jobOpening);
        $highestReachedStage = DashboardHRCandidatesViewHelper::determineHighestStage($candidate);
        $stageInfo = DashboardHRCandidatesViewHelper::stage($highestReachedStage);
        $participants = DashboardHRCandidatesViewHelper::participants($highestReachedStage);
        $notes = DashboardHRCandidatesViewHelper::notes($company, $highestReachedStage, $loggedEmployee);

        return Inertia::render('Dashboard/HR/JobOpenings/Candidates/Show', [
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
            'jobOpening' => $jobOpeningInfo,
            'candidate' => $candidateInfo,
            'otherJobOpenings' => $otherJobOpenings,
            'stage' => $stageInfo,
            'participants' => $participants,
            'notes' => $notes,
            'tab' => 'recruiting',
        ]);
    }

    /**
     * Show the detail of a job opening.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @param integer $candidateStageId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $jobOpeningId, int $candidateId, int $candidateStageId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $stage = (new ProcessCandidateStage)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'job_opening_id' => $jobOpeningId,
            'candidate_id' => $candidateId,
            'candidate_stage_id' => $candidateStageId,
            'accepted' => $request->input('accepted'),
        ]);

        return response()->json([
            'url' => route('dashboard.hr.candidates.stage.show', [
                'company' => $companyId,
                'jobOpening' => $jobOpeningId,
                'candidate' => $candidateId,
                'stage' => $stage,
            ]),
        ]);
    }

    /**
     * Show the detail of a job opening at a given stage.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @param integer $stageId
     * @return mixed
     */
    public function showStage(Request $request, int $companyId, int $jobOpeningId, int $candidateId, int $stageId): mixed
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($loggedEmployee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        try {
            $jobOpening = JobOpening::where('company_id', $company->id)
                ->with('team')
                ->with('position')
                ->with('sponsors')
                ->findOrFail($jobOpeningId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $candidate = Candidate::where('company_id', $company->id)
                ->findOrFail($candidateId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $candidateStage = CandidateStage::where('candidate_id', $candidate->id)
                ->findOrFail($stageId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $jobOpeningInfo = DashboardHRCandidatesViewHelper::jobOpening($company, $jobOpening);
        $candidateInfo = DashboardHRCandidatesViewHelper::candidate($company, $jobOpening, $candidate);
        $otherJobOpenings = DashboardHRCandidatesViewHelper::otherJobOpenings($company, $candidate, $jobOpening);
        $stageInfo = DashboardHRCandidatesViewHelper::stage($candidateStage);
        $participants = DashboardHRCandidatesViewHelper::participants($candidateStage);
        $notes = DashboardHRCandidatesViewHelper::notes($company, $candidateStage, $loggedEmployee);

        return Inertia::render('Dashboard/HR/JobOpenings/Candidates/Show', [
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
            'jobOpening' => $jobOpeningInfo,
            'candidate' => $candidateInfo,
            'otherJobOpenings' => $otherJobOpenings,
            'stage' => $stageInfo,
            'participants' => $participants,
            'notes' => $notes,
            'tab' => 'recruiting',
        ]);
    }

    /**
     * Search the potential participants.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @param integer $stageId
     */
    public function searchParticipants(Request $request, int $companyId, int $jobOpeningId, int $candidateId, int $stageId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($employee->permission_level > config('officelife.permission_level.hr')) {
            return response()->json([], 403);
        }

        try {
            JobOpening::where('company_id', $loggedCompany->id)
                ->with('team')
                ->with('position')
                ->with('sponsors')
                ->findOrFail($jobOpeningId);
        } catch (ModelNotFoundException $e) {
            return response()->json([], 403);
        }

        try {
            $candidate = Candidate::where('company_id', $loggedCompany->id)
                ->findOrFail($candidateId);
        } catch (ModelNotFoundException $e) {
            return response()->json([], 403);
        }

        try {
            $candidateStage = CandidateStage::where('candidate_id', $candidate->id)
                ->findOrFail($stageId);
        } catch (ModelNotFoundException $e) {
            return response()->json([], 403);
        }

        $potential = DashboardHRCandidatesViewHelper::potentialParticipants(
            $loggedCompany,
            $candidateStage,
            $request->input('searchTerm')
        );

        return response()->json([
            'data' => $potential,
        ], 200);
    }

    /**
     * Actually assign a participant to the recruiting stage.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @param integer $stageId
     * @return mixed
     */
    public function assignParticipant(Request $request, int $companyId, int $jobOpeningId, int $candidateId, int $stageId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $candidateStageParticipant = (new CreateCandidateStageParticipant)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'job_opening_id' => $jobOpeningId,
            'candidate_id' => $candidateId,
            'candidate_stage_id' => $stageId,
            'participant_id' => $request->input('employeeId'),
        ]);

        return response()->json([
            'data' => [
                'id' => $candidateStageParticipant->participant->id,
                'name' => $candidateStageParticipant->participant->name,
                'avatar' => ImageHelper::getAvatar($candidateStageParticipant->participant, 32),
                'participant_id' => $candidateStageParticipant->id,
            ],
        ], 200);
    }

    /**
     * Remove a participant from the recruiting stage.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @param integer $stageId
     * @param integer $participantId
     * @return mixed
     */
    public function removeParticipant(Request $request, int $companyId, int $jobOpeningId, int $candidateId, int $stageId, int $participantId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new DestroyCandidateStageParticipant)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'job_opening_id' => $jobOpeningId,
            'candidate_id' => $candidateId,
            'candidate_stage_id' => $stageId,
            'candidate_stage_participant_id' => $participantId,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Create a new note.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @param integer $stageId
     * @return mixed
     */
    public function notes(Request $request, int $companyId, int $jobOpeningId, int $candidateId, int $stageId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $note = (new CreateCandidateStageNote)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'job_opening_id' => $jobOpeningId,
            'candidate_id' => $candidateId,
            'candidate_stage_id' => $stageId,
            'note' => $request->input('note'),
        ]);

        return response()->json([
            'data' => [
                'id' => $note->id,
                'note' => $note->note,
                'parsed_note' => StringHelper::parse($note->note),
                'created_at' => DateHelper::formatDate($note->created_at),
                'author' => [
                    'id' => $note->author->id,
                    'name' => $note->author->name,
                    'avatar' => ImageHelper::getAvatar($note->author, 32),
                    'url' => route('employees.show', [
                        'company' => $loggedCompany,
                        'employee' => $note->author,
                    ]),
                ],
                'permissions' => [
                    'can_edit' => $loggedEmployee->id === $note->author->id,
                    'can_destroy' => $loggedEmployee->id === $note->author->id,
                ],
            ],
        ], 200);
    }

    /**
     * Update a note.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @param integer $stageId
     * @param integer $noteId
     * @return mixed
     */
    public function updateNote(Request $request, int $companyId, int $jobOpeningId, int $candidateId, int $stageId, int $noteId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $note = (new UpdateCandidateStageNote)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'job_opening_id' => $jobOpeningId,
            'candidate_id' => $candidateId,
            'candidate_stage_id' => $stageId,
            'candidate_stage_note_id' => $noteId,
            'note' => $request->input('note'),
        ]);

        return response()->json([
            'data' => [
                'id' => $note->id,
                'note' => $note->note,
                'parsed_note' => StringHelper::parse($note->note),
                'created_at' => DateHelper::formatShortDateWithTime($note->created_at),
                'author' => [
                    'id' => $note->author->id,
                    'name' => $note->author->name,
                    'avatar' => ImageHelper::getAvatar($note->author, 32),
                    'url' => route('employees.show', [
                        'company' => $loggedCompany,
                        'employee' => $note->author,
                    ]),
                ],
                'permissions' => [
                    'can_edit' => $loggedEmployee->id === $note->author->id,
                    'can_destroy' => $loggedEmployee->id === $note->author->id,
                ],
            ],
        ], 200);
    }

    /**
     * Destroy a note.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @param integer $stageId
     * @param integer $noteId
     * @return mixed
     */
    public function destroyNote(Request $request, int $companyId, int $jobOpeningId, int $candidateId, int $stageId, int $noteId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new DestroyCandidateStageNote)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'job_opening_id' => $jobOpeningId,
            'candidate_id' => $candidateId,
            'candidate_stage_id' => $stageId,
            'candidate_stage_note_id' => $noteId,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
