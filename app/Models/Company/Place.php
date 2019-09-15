<?php

namespace App\Models\Company;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use Searchable;

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
        'is_dummy',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_dummy' => 'boolean',
    ];
}
