<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AskMeAnythingSession extends Model
{
    use HasFactory;

    protected $table = 'ask_me_anything_sessions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'happened_at',
        'active',
        'theme',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'happened_at',
    ];

    /**
     * Get the company record associated with the session.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the questions associated with the session.
     *
     * @return HasMany
     */
    public function questions()
    {
        return $this->hasMany(AskMeAnythingQuestion::class);
    }
}
