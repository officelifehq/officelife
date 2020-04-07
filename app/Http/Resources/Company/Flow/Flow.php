<?php

namespace App\Http\Resources\Company\Flow;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Company\Flow\Step as StepResource;

class Flow extends JsonResource
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
            'object' => 'flow',
            'name' => $this->name,
            'type' => $this->type,
            'steps' => [
                'count' => $this->steps->count(),
                'data' => StepResource::collection($this->steps),
            ],
            'company' => [
                'id' => $this->company_id,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
