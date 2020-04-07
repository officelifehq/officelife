<?php

namespace App\Http\Resources\Company\CompanyNews;

use App\Helpers\DateHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Employee;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyNews extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $employeeFound = Employee::where('id', $this->author_id)->first();

        return [
            'id' => $this->id,
            'object' => 'companynews',
            'title' => $this->title,
            'content' => $this->content,
            'parsed_content' => StringHelper::parse($this->content),
            'author' => [
                'id' => is_null($employeeFound) ? null : $employeeFound->id,
                'name' => is_null($employeeFound) ? $this->author_name : $employeeFound->name,
            ],
            'company' => [
                'id' => $this->company_id,
            ],
            'localized_created_at' => DateHelper::formatShortDateWithTime($this->created_at),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
