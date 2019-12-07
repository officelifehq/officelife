<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamUsefulLink extends Model
{
    protected $table = 'team_useful_links';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id',
        'type',
        'label',
        'url',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_dummy' => 'boolean',
    ];

    /**
     * Get the team record associated with the team useful link object.
     *
     * @return BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Return the label attribute.
     * The label attribute can be null, so if that's the case, we'll return the
     * URL instead.
     *
     * @var mixed $value
     * @return string
     * @param mixed $value
     */
    public function getLabelAttribute($value): string
    {
        if (!$value) {
            return $this->url;
        }

        return $value;
    }
}
