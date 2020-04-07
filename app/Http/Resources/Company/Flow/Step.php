<?php

namespace App\Http\Resources\Company\Flow;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Company\Flow\Action as ActionResource;

class Step extends JsonResource
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
            'object' => 'step',
            'number' => $this->number,
            'unit_of_time' => $this->unit_of_time,
            'modifier' => $this->modifier,
            'real_number_of_days' => $this->real_number_of_days,
            'actions' => [
                'count' => $this->actions->count(),
                'data' => ActionResource::collection($this->actions),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
