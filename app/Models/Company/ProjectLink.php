<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectLink extends Model
{
    use HasFactory;

    protected $table = 'project_links';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id',
        'type',
        'label',
        'url',
    ];

    /**
     * Get the project record associated with the project link.
     *
     * @return BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Return the label attribute.
     * The label attribute can be null, so if that's the case, we'll return the
     * URL instead.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getLabelAttribute($value): string
    {
        if (! $value) {
            return $this->url;
        }

        return $value;
    }
}
