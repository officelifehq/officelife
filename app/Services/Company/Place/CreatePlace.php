<?php

namespace App\Services\Company\Place;

use App\Models\Company\Place;
use App\Services\BaseService;
use Illuminate\Support\Facades\DB;
use App\Jobs\FetchAddressGeocoding;

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
            'country_id' => 'nullable|integer|exists:countries,id',
            'placable_id' => 'required|integer',
            'placable_type' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
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
            config('kakene.authorizations.user')
        );

        $place = $this->addPlace($data);

        if ($this->valueOrFalse($data, 'is_active')) {
            $this->setActive($place);
        }

        $this->geocodePlace($place);

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
            'placable_id' => $data['placable_id'],
            'placable_type' => $data['placable_type'],
            'is_active' => $this->valueOrFalse($data, 'is_active'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);
    }

    /**
     * Set a place as active for the placable object.
     * Check all the previous places for this entity and set them to inactive.
     *
     * @param Place $place
     * @return void
     */
    private function setActive(Place $place)
    {
        DB::table('places')
            ->where('placable_id', $place->placable_id)
            ->where('placable_type', $place->placable_type)
            ->where('id', '!=', $place->id)
            ->update(['is_active' => false]);
    }

    /**
     * Fetch the longitude/latitude for the new place.
     * This is placed on a queue so it doesn't slow down the app.
     *
     * @param Place $place
     * @return void
     */
    private function geocodePlace(Place $place)
    {
        FetchAddressGeocoding::dispatch($place)->onQueue('low');
    }
}
