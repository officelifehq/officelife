<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;

class AdminProjectManagementViewHelper
{
    /**
     * Get all the issue types in the company.
     *
     * @param Company $company
     * @return array
     */
    public static function issueTypes(Company $company): array
    {
        $types = $company->issueTypes()->orderBy('name', 'asc')->get();
        $typesCollection = collect([]);
        foreach ($types as $type) {
            $typesCollection->push([
                'id' => $type->id,
                'name' => $type->name,
                'icon_hex_color' => $type->icon_hex_color,
                'url' => [
                    'update' => route('projectmanagement.update', [
                        'company' => $company->id,
                        'type' => $type->id,
                    ]),
                    'destroy' => route('projectmanagement.destroy', [
                        'company' => $company->id,
                        'type' => $type->id,
                    ]),
                ],
            ]);
        }

        return [
            'issue_types' => $typesCollection,
            'url_create' => route('projectmanagement.store', [
                'company' => $company->id,
            ]),
        ];
    }
}
