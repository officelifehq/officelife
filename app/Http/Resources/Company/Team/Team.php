<?php

namespace App\Http\Resources\Company\Team;

use App\Helpers\StringHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class Team extends JsonResource
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
            'object' => 'team',
            'name' => $this->name,
            'raw_description' => is_null($this->description) ? null : $this->description,
            'parsed_description' => is_null($this->description) ? null : StringHelper::parse($this->description),
            'company' => [
                'id' => $this->company_id,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
