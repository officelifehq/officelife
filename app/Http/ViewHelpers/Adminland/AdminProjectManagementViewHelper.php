<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;

class AdminProjectManagementViewHelper
{
    /**
     * Get all the issue types in the company.
     *
     * @param mixed $company
     * @return array
     */
    public static function issueTypes($company): array
    {
        $types = $company->issueTypes()->orderBy('name', 'asc')->get();
        $typesCollection = collect([]);
        foreach ($types as $type) {
            $typesCollection->push([
                'id' => $type->id,
                'name' => $type->name,
                'icon_hex_color' => $type->icon_hex_color,
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
