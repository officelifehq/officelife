<?php

namespace App\Services\Company\Place;

use Illuminate\Support\Str;
use App\Models\Company\Place;
use App\Services\BaseService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\HttpClientException;

class GetGPSCoordinate extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'place_id' => 'required|integer|exists:places,id',
        ];
    }

    /**
     * Get the latitude and longitude from a place.
     * This method uses LocationIQ to process the geocoding.
     * Should always be done through a job, and not be called directly.
     * You should use the FetchAddressGeocoding job for this.
     *
     * @param array        $data
     *
     * @return Place|null
     */
    public function execute(array $data)
    {
        $this->validateRules($data);

        $place = Place::findOrFail($data['place_id']);

        return $this->query($place);
    }

    /**
     * Build the query to send with the API call.
     *
     * @param Place $place
     *
     * @return string|null
     */
    private function buildQuery(Place $place): ?string
    {
        if (is_null(config('officelife.location_iq_api_key'))) {
            return null;
        }

        $query = http_build_query([
            'format' => 'json',
            'key' => config('officelife.location_iq_api_key'),
            'q' => $place->getCompleteAddress(),
        ]);

        return Str::finish(config('officelife.location_iq_url'), '/').'search.php?'.$query;
    }

    /**
     * Actually make the call to the reverse geocoding API.
     *
     * @param Place $place
     *
     * @return Place|null
     */
    private function query(Place $place): ?Place
    {
        $query = $this->buildQuery($place);

        if (is_null($query)) {
            return null;
        }

        try {
            $response = Http::get($query);
            $response->throw();

            $place->latitude = $response->json('0.lat');
            $place->longitude = $response->json('0.lon');
            $place->save();

            return $place;
        } catch (HttpClientException $e) {
            Log::error('Error calling location_iq: '.$e);
        }

        return null;
    }
}
