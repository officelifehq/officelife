<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Helpers\FileHelper;
use App\Models\Company\Project;
use Illuminate\Support\Collection;

class ProjectFilesViewHelper
{
    /**
     * Collection containing the information about the files in the project.
     *
     * @param Project $project
     * @return Collection
     */
    public static function index(Project $project): Collection
    {
        $files = $project->files()
            ->orderBy('id', 'desc')
            ->get();

        $filesCollection = collect([]);
        foreach ($files as $file) {
            $filesCollection->push([
                'id' => $file->id,
                'size' => FileHelper::getSize($file->size),
                'filename' => $file->name,
                'download_url' => $file->cdn_url,
            ]);
        }

        return $filesCollection;
    }
}
