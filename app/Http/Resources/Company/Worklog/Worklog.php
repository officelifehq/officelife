<?php

namespace App\Http\Resources\Company\Worklog;

use App\Helpers\StringHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class Worklog extends JsonResource
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
            'object' => 'worklog',
            'employee' => new EmployeeResource($this->employee),
            'content' => $this->content,
            'parsed_content' => StringHelper::parse($this->content),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
