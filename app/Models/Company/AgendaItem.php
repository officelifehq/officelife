<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaItem extends Model
{
    use HasFactory;

    protected $table = 'agenda_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meeting_id',
        'position',
        'checked',
        'summary',
        'description',
        'presented_by_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'checked' => 'boolean',
    ];

    /**
     * Get the meeting record associated with the agenda item.
     *
     * @return BelongsTo
     */
    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }

    /**
     * Get the employee record associated with the agenda item.
     *
     * @return HasOne
     */
    public function presenter()
    {
        return $this->hasOne(Employee::class, 'id', 'presented_by_id');
    }

    /**
     * Get the decisions associated with the agenda item.
     *
     * @return HasMany
     */
    public function decisions()
    {
        return $this->hasMany(MeetingDecision::class);
    }
}
