<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
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
        'is_dummy',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_dummy' => 'boolean',
    ];

    /**
     * Get the owning placable model.
     */
    public function placable()
    {
        return $this->morphTo();
    }
}
