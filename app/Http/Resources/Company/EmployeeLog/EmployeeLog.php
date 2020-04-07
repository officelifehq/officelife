<?php

namespace App\Http\Resources\Company\EmployeeLog;

use App\Models\Company\Employee;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeLog extends JsonResource
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
            'object' => 'employeelog',
            'action' => $this->action,
            'objects' => json_decode($this->objects),
            'localized_content' => $this->content,
            'author' => [
                'id' => is_null($employeeFound) ? null : $employeeFound->id,
                'name' => is_null($employeeFound) ? $this->author_name : $employeeFound->name,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
