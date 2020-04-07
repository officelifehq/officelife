<?php

namespace App\Http\Resources\Company\Employee;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeListWithoutTeams extends JsonResource
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
            'object' => 'employee',
            'name' => $this->name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'permission_level' => $this->permission_level,
            'position' => $this->position,
            'avatar' => $this->avatar,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
