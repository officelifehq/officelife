<?php

namespace App\Http\Resources\Company\Morale;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class Morale extends JsonResource
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
            'object' => 'morale',
            'emotion' => $this->emotion,
            'translated_emotion' => $this->translated_emotion,
            'comment' => $this->comment,
            'employee' => new EmployeeResource($this->employee),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
