<?php

namespace App\Http\Resources\Company\Flow;

use Illuminate\Http\Resources\Json\JsonResource;

class Action extends JsonResource
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
            'type' => $this->type,
            'recipient' => $this->recipient,
            'recipient_specific_information' => $this->recipient_specific_information,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
