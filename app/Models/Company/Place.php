<?php

namespace App\Models\Company;

use App\Helpers\MapHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Place extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street',
        'city',
        'province',
        'postal_code',
        'country_id',
        'latitude',
        'longitude',
        'placable_id',
        'placable_type',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the owning placable model.
     *
     * @return MorphTo
     */
    public function placable()
    {
        return $this->morphTo();
    }

    /**
     * Get the country record associated with the place.
     *
     * @return BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Transform the object to an array representing this object.
     *
     * @return array
     */
    public function toObject(): array
    {
        return [
            'id' => $this->id,
            'readable' => $this->getCompleteAddress(),
            'partial' => $this->getPartialAddress(),
            'street' => $this->street,
            'city' => $this->city,
            'province' => $this->province,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'openstreetmap_url' => $this->getMapUrl(),
            'employee_cover_image_url' => $this->getStaticMapImage(7, 600, 130),
            'created_at' => $this->created_at,
        ];
    }

    /**
     * Get the address as a sentence.
     *
     * @return string
     */
    public function getCompleteAddress(): string
    {
        $address = '';

        if (! is_null($this->street)) {
            $address = $this->street;
        }

        if (! is_null($this->city)) {
            $address .= ' '.$this->city;
        }

        if (! is_null($this->province)) {
            $address .= ' '.$this->province;
        }

        if (! is_null($this->postal_code)) {
            $address .= ' '.$this->postal_code;
        }

        if (! is_null($this->country)) {
            $address .= ' '.$this->getCountryName();
        }

        // trim extra whitespaces inside the address
        $address = preg_replace('/\s+/', ' ', $address);

        return $address;
    }

    /**
     * Get the country of the place.
     *
     * @return string|null
     */
    public function getCountryName(): ?string
    {
        if ($this->country) {
            return $this->country->name;
        }

        return null;
    }

    /**
     * Get the partial address, used to show basic information to other employees.
     *
     * @return string|null
     */
    public function getPartialAddress(): ?string
    {
        $address = '';

        if (! is_null($this->city)) {
            $address = $this->city;
        }

        if (! is_null($this->country)) {
            $address .= ' ('.$this->getCountryName().')';
        }

        // trim extra whitespaces inside the address
        $address = preg_replace('/\s+/', ' ', $address);

        return $address;
    }

    /**
     * Get the static image map for this place.
     *
     * @param int $zoom
     * @param int $width
     * @param int $height
     *
     * @return string|null
     */
    public function getStaticMapImage(int $zoom, int $width, int $height): ?string
    {
        return MapHelper::getStaticImage($this, $width, $height, $zoom);
    }

    /**
     * Get the URL on OpenStreetMap for the partial URL.
     *
     * @param bool $completeAddress
     * @return string
     */
    public function getMapUrl(bool $completeAddress = true): string
    {
        if ($completeAddress) {
            $place = $this->getCompleteAddress();
        } else {
            $place = $this->getPartialAddress();
        }

        $place = urlencode($place);

        return "https://www.openstreetmap.org/search?query={$place}";
    }
}
