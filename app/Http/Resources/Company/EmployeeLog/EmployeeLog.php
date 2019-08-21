<?php

namespace App\Http\Resources\Company\EmployeeLog;

use App\Models\User\User;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeLog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $userFound = User::where('id', $this->object->{'author_id'})->first();

        return [
            'id' => $this->id,
            'object' => 'employeelog',
            'action' => $this->action,
            'objects' => json_decode($this->objects),
            'localized_content' => $this->content,
            'author' => [
                'id' => is_null($userFound) ? null : $userFound->id,
                'name' => is_null($userFound) ? $this->object->{'author_name'} : $userFound->name,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
