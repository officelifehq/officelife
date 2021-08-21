<?php

namespace App\Http\Controllers\Jobs;

use Inertia\Inertia;
use App\Helpers\FileHelper;
use App\Models\Company\File;
use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use Illuminate\Http\JsonResponse;
use App\Models\Company\JobOpening;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\File\UploadFile;
use App\Http\ViewHelpers\Jobs\JobsCompanyViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\CreateCandidate;
use App\Services\Company\Adminland\JobOpening\AddFileToCandidate;
use App\Services\Company\Adminland\JobOpening\DestroyCandidateDuringApplicationProcess;
use App\Services\Company\Adminland\JobOpening\DestroyCandidateFileDuringApplicationProcess;

class JobsCompanyController extends Controller
{
    /**
     * Shows all the jobs openings in the given company.
     *
     * @param Request $request
     * @param string $slug
     * @return mixed
     */
    public function index(Request $request, string $slug)
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        $data = JobsCompanyViewHelper::index($company);

        return Inertia::render('Jobs/Company/Index', [
            'data' => $data,
        ]);
    }

    /**
     * Shows the details of the job opening.
     *
     * @param Request $request
     * @param string $slug
     * @param string $jobOpeningSlug
     * @return mixed
     */
    public function show(Request $request, string $slug, string $jobOpeningSlug)
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        try {
            $opening = JobOpening::where('slug', $jobOpeningSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        if ($request->query('ignore') == false) {
            $opening->increment('page_views');
        }

        $data = JobsCompanyViewHelper::show($company, $opening);

        return Inertia::render('Jobs/Company/Show', [
            'data' => $data,
        ]);
    }

    /**
     * Shows the Apply to a job page.
     *
     * @param Request $request
     * @param string $slug
     * @param string $jobOpeningSlug
     * @return mixed
     */
    public function apply(Request $request, string $slug, string $jobOpeningSlug)
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        try {
            $opening = JobOpening::where('slug', $jobOpeningSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        $data = JobsCompanyViewHelper::apply($company, $opening);

        return Inertia::render('Jobs/Company/Apply', [
            'data' => $data,
        ]);
    }

    /**
     * Store the candidate.
     *
     * @param Request $request
     * @param string $slug
     * @param string $jobOpeningSlug
     * @return mixed
     */
    public function store(Request $request, string $slug, string $jobOpeningSlug)
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        try {
            $opening = JobOpening::where('slug', $jobOpeningSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        $data = [
            'company_id' => $company->id,
            'job_opening_id' => $opening->id,
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'url' => $request->input('url'),
            'desired_salary' => $request->input('desired_salary'),
            'notes' => $request->input('notes'),
        ];

        $candidate = (new CreateCandidate)->execute($data);

        return response()->json([
            'url' => route('jobs.company.cv', [
                'company' => $company->slug,
                'job' => $opening->slug,
                'candidate' => $candidate->uuid,
            ]),
        ], 200);
    }

    /**
     * Shows the Upload documents page.
     *
     * @param Request $request
     * @param string $slug
     * @param string $jobOpeningSlug
     * @param string $candidateSlug
     * @return mixed
     */
    public function cv(Request $request, string $slug, string $jobOpeningSlug, string $candidateSlug)
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        try {
            $opening = JobOpening::where('slug', $jobOpeningSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        try {
            $candidate = Candidate::where('uuid', $candidateSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        $data = JobsCompanyViewHelper::apply($company, $opening);

        return Inertia::render('Jobs/Company/Upload', [
            'data' => $data,
            'candidate' => [
                'id' => $candidate->id,
                'slug' => $candidate->uuid,
            ],
            'uploadcarePublicKey' => config('officelife.uploadcare_public_key'),
        ]);
    }

    /**
     * Store a document.
     *
     * @param Request $request
     * @param string $slug
     * @param string $jobOpeningSlug
     * @param string $candidateSlug
     * @return JsonResponse|null
     */
    public function storeCv(Request $request, string $slug, string $jobOpeningSlug, string $candidateSlug): ?JsonResponse
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        try {
            $opening = JobOpening::where('slug', $jobOpeningSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        try {
            $candidate = Candidate::where('uuid', $candidateSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        // get the administrator of the company, so we can upload the document
        $administrator = Employee::where('company_id', $company->id)
            ->where('permission_level', config('officelife.permission_level.administrator'))
            ->first();

        $file = (new UploadFile)->execute([
            'company_id' => $company->id,
            'author_id' => $administrator->id,
            'uuid' => $request->input('uuid'),
            'name' => $request->input('name'),
            'original_url' => $request->input('original_url'),
            'cdn_url' => $request->input('cdn_url'),
            'mime_type' => $request->input('mime_type'),
            'size' => $request->input('size'),
            'type' => 'candidate_file',
        ]);

        (new AddFileToCandidate)->execute([
            'company_id' => $company->id,
            'candidate_id' => $candidate->id,
            'file_id' => $file->id,
        ]);

        return response()->json([
            'data' => [
                'id' => $file->id,
                'size' => FileHelper::getSize($file->size),
                'filename' => $file->name,
                'download_url' => $file->cdn_url,
            ],
        ], 200);
    }

    /**
     * Destroy a document.
     *
     * @param Request $request
     * @param string $slug
     * @param string $jobOpeningSlug
     * @param string $candidateSlug
     * @param string $fileId
     * @return JsonResponse|null
     */
    public function destroyCv(Request $request, string $slug, string $jobOpeningSlug, string $candidateSlug, string $fileId): ?JsonResponse
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        try {
            $opening = JobOpening::where('slug', $jobOpeningSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        try {
            $candidate = Candidate::where('uuid', $candidateSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        try {
            $file = File::where('company_id', $company->id)
                ->findOrFail($fileId);
        } catch (ModelNotFoundException $e) {
            return null;
        }

        (new DestroyCandidateFileDuringApplicationProcess)->execute([
            'company_id' => $company->id,
            'candidate_id' => $candidate->id,
            'file_id' => $file->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Destroy the candidate during the application process.
     *
     * @param Request $request
     * @param string $slug
     * @param string $jobOpeningSlug
     * @param string $candidateSlug
     * @return JsonResponse|null
     */
    public function destroy(Request $request, string $slug, string $jobOpeningSlug, string $candidateSlug): ?JsonResponse
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        try {
            $opening = JobOpening::where('slug', $jobOpeningSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        try {
            $candidate = Candidate::where('uuid', $candidateSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        (new DestroyCandidateDuringApplicationProcess)->execute([
            'company_id' => $company->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
        ]);

        return response()->json([
            'url' => route('jobs.company.show', [
                'company' => $company->slug,
                'job' => $opening->slug,
            ]),
        ], 200);
    }

    /**
     * Finalize the application process.
     *
     * @param Request $request
     * @param string $slug
     * @param string $jobOpeningSlug
     * @param string $candidateSlug
     * @return JsonResponse|null
     */
    public function finalizeApplication(Request $request, string $slug, string $jobOpeningSlug, string $candidateSlug): ?JsonResponse
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        try {
            $opening = JobOpening::where('slug', $jobOpeningSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        try {
            $candidate = Candidate::where('uuid', $candidateSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return null;
        }

        $candidate->application_completed = true;
        $candidate->save();

        return response()->json([
            'url' => route('jobs.company.success', [
                'company' => $company->slug,
                'job' => $opening->slug,
                'candidate' => $candidate->uuid,
            ]),
        ], 200);
    }

    /**
     * Shows Success page.
     *
     * @param Request $request
     * @param string $slug
     * @param string $jobOpeningSlug
     * @param string $candidateSlug
     * @return mixed
     */
    public function success(Request $request, string $slug, string $jobOpeningSlug, string $candidateSlug)
    {
        try {
            $company = Company::where('slug', $slug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        try {
            $opening = JobOpening::where('slug', $jobOpeningSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        try {
            $candidate = Candidate::where('uuid', $candidateSlug)
                ->where('company_id', $company->id)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('jobs');
        }

        $data = JobsCompanyViewHelper::apply($company, $opening);

        return Inertia::render('Jobs/Company/Success', [
            'data' => $data,
            'candidate' => [
                'id' => $candidate->id,
                'slug' => $candidate->uuid,
            ],
        ]);
    }
}
