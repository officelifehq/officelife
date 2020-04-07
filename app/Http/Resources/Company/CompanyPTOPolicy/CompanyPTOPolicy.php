<?php

namespace App\Http\Resources\Company\CompanyPTOPolicy;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyPTOPolicy extends JsonResource
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
            'object' => 'companyptopolicy',
            'year' => $this->year,
            'total_worked_days' => $this->total_worked_days,
            'default_amount_of_allowed_holidays' => $this->default_amount_of_allowed_holidays,
            'default_amount_of_sick_days' => $this->default_amount_of_sick_days,
            'default_amount_of_pto_days' => $this->default_amount_of_pto_days,
            'company' => [
                'id' => $this->company_id,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
