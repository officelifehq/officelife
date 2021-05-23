<?php

namespace App\Http\Controllers\Company\Employee\Edit;

use Inertia\Response;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\File\UploadFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EmployeeEditAvatarController extends Controller
{
    /**
     * Update the avatar.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $employee = Employee::where('company_id', $loggedCompany->id)
                ->findOrFail($employeeId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $file = (new UploadFile)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'uuid' => $request->input('uuid'),
            'name' => $request->input('name'),
            'original_url' => $request->input('original_url'),
            'cdn_url' => $request->input('cdn_url'),
            'mime_type' => $request->input('mime_type'),
            'size' => $request->input('size'),
            'type' => 'avatar',
        ]);

        $employee->avatar_file_id = $file->id;
        $employee->save();
        $employee->refresh();

        return response()->json([
            'avatar' => ImageHelper::getAvatar($employee),
        ], 200);
    }
}
