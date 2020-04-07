<?php

namespace App\Http\Resources\Company\EmployeeStatus;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeStatus extends JsonResource
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
        return [
            'id' => $this->id,
            'object' => 'employee_status',
            'name' => $this->name,
            'company' => [
                'id' => $this->company_id,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
