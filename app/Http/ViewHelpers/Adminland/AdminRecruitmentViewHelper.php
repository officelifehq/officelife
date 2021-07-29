<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;
use Illuminate\Support\Collection;
use App\Models\Company\RecruitingStageTemplate;

class AdminRecruitmentViewHelper
{
    /**
     * Get all the recruiting stage templates in the company.
     *
     * @param Company $company
     * @return Collection
     */
    public static function index(Company $company): ?Collection
    {
        $templates = $company->recruitingStageTemplates()
            ->with('stages')
            ->orderBy('name')
            ->get();

        $templatesCollection = collect();
        foreach ($templates as $template) {
            $stages = $template->stages;
            $stagesCollection = collect();
            foreach ($stages as $stage) {
                $stagesCollection->push([
                    'id' => $stage->id,
                    'name' => $stage->name,
                    'position' => $stage->position,
                ]);
            }

            $templatesCollection->push([
                'id' => $template->id,
                'name' => $template->name,
                'stages' => $stagesCollection,
                'url' => route('recruitment.show', [
                    'company' => $company,
                    'template' => $template,
                ]),
            ]);
        }

        return $templatesCollection;
    }

    /**
     * Get the information about the given template.
     *
     * @param Company $company
     * @return array
     */
    public static function show(Company $company, RecruitingStageTemplate $template): ?array
    {
        $stages = $template->stages;
        $stagesCollection = collect();
        foreach ($stages as $stage) {
            $stagesCollection->push([
                'id' => $stage->id,
                'name' => $stage->name,
                'position' => $stage->position,
            ]);
        }

        return [
            'id' => $template->id,
            'name' => $template->name,
            'stages' => $stagesCollection,
        ];
    }
}
