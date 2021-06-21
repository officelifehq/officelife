<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wiki_id',
        'title',
        'content',
    ];

    /**
     * Get the wiki record associated with the page.
     *
     * @return BelongsTo
     */
    public function wiki()
    {
        return $this->belongsTo(Wiki::class);
    }
}
