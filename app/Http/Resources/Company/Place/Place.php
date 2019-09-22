<?php

namespace App\Http\Resources\Company\Place;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Company\Country\Country as CountryResource;

class Place extends JsonResource
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
            'object' => 'place',
            'readable' => $this->getAddressAsString(),
            'street' => $this->street,
            'city' => $this->city,
            'province' => $this->province,
            'postal_code' => $this->postal_code,
            'country' => new CountryResource($this->country),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
