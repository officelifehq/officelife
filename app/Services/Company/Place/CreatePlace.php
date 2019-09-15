<?php

namespace App\Services\Company\Place;

use App\Models\Company\Place;
use App\Services\BaseService;

class CreatePlace extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'street' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country_id' => 'nullable|integer|max:255',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a place.
     *
     * @param array $data
     * @return Place
     */
    public function execute(array $data) : Place
    {
        $this->validate($data);

        $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.user')
        );

        $place = $this->addPlace($data);

        return $place;
    }

    /**
     * Actually create the place.
     *
     * @param array $data
     * @return Place
     */
    private function addPlace(array $data) : Place
    {
        return Place::create([
            'street' => $this->nullOrValue($data, 'street'),
            'city' => $this->nullOrValue($data, 'city'),
            'province' => $this->nullOrValue($data, 'province'),
            'postal_code' => $this->nullOrValue($data, 'postal_code'),
            'country_id' => $this->nullOrValue($data, 'country_id'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }
}
