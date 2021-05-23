<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Helpers\DateHelper;
use App\Helpers\FileHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class ProjectFilesViewHelper
{
    /**
     * Collection containing the information about the files in the project.
     *
     * @param Project $project
     * @param Employee $employee
     * @return Collection
     */
    public static function index(Project $project, Employee $employee): Collection
    {
        $company = $project->company;

        $files = $project->files()
            ->with('uploader')
            ->orderBy('id', 'desc')
            ->get();

        $filesCollection = collect([]);
        foreach ($files as $file) {
            $uploader = $file->uploader;

            $filesCollection->push([
                'id' => $file->id,
                'size' => FileHelper::getSize($file->size),
                'filename' => $file->name,
                'download_url' => $file->cdn_url,
                'uploader' => $uploader ? [
                    'id' => $uploader->id,
                    'name' => $uploader->name,
                    'avatar' => ImageHelper::getAvatar($uploader, 24),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $uploader,
                    ]),
                ] : $file->uploader_name,
                'created_at' => DateHelper::formatDate($file->created_at, $employee->timezone),
            ]);
        }

        return $filesCollection;
    }
}
