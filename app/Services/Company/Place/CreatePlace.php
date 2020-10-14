<?php

namespace App\Services\Company\Place;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Place;
use App\Services\BaseService;
use App\Jobs\LogEmployeeAudit;
use Illuminate\Support\Facades\DB;
use App\Jobs\FetchAddressGeocoding;

class CreatePlace extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
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
        ];
    }

    /**
     * Create a place.
     *
     * @param array $data
     * @return Place
     */
    public function execute(array $data): Place
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asNormalUser()
            ->canExecuteService();

        $place = $this->addPlace($data);

        if ($this->valueOrFalse($data, 'is_active')) {
            $this->setActive($place);
        }

        $this->geocodePlace($place);

        if ($data['placable_type'] == 'App\Models\Company\Employee') {
            $this->addLog($data, $place);
        }

        return $place;
    }

    /**
     * Actually create the place.
     *
     * @param array $data
     *
     * @return Place
     */
    private function addPlace(array $data): Place
    {
        return Place::create([
            'street' => $this->valueOrNull($data, 'street'),
            'city' => $this->valueOrNull($data, 'city'),
            'province' => $this->valueOrNull($data, 'province'),
            'postal_code' => $this->valueOrNull($data, 'postal_code'),
            'country_id' => $this->valueOrNull($data, 'country_id'),
            'placable_id' => $data['placable_id'],
            'placable_type' => $data['placable_type'],
            'is_active' => $this->valueOrFalse($data, 'is_active'),
        ]);
    }

    /**
     * Set a place as active for the placable object.
     * Check all the previous places for this entity and set them to inactive.
     *
     * @param Place $place
     */
    private function setActive(Place $place): void
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
     */
    private function geocodePlace(Place $place): void
    {
        FetchAddressGeocoding::dispatch($place)->onQueue('low');
    }

    /**
     * Add logs.
     *
     * @param array $data
     * @param Place $place
     */
    private function addLog(array $data, Place $place): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'address_added_to_employee',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'place_id' => $place->id,
                'partial_address' => $place->getPartialAddress(),
            ]),
        ])->onQueue('low');

        LogEmployeeAudit::dispatch([
            'employee_id' => $data['placable_id'],
            'action' => 'address_added',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'place_id' => $place->id,
                'partial_address' => $place->getPartialAddress(),
            ]),
        ])->onQueue('low');
    }
}
