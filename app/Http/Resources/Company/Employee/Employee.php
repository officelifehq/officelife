<?php

namespace App\Http\Resources\Company\Employee;

use Illuminate\Http\Resources\Json\JsonResource;

class Employee extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'object' => 'employee',
            'name' => $this->name,
            'email' => $this->email,
            'permission_level' => $this->getPermissionLevel(),
            'avatar' => $this->avatar,
            'company' => [
                'id' => $this->company_id,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
