<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeetingDecision extends Model
{
    use HasFactory;

    protected $table = 'meeting_decisions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agenda_item_id',
        'description',
    ];

    /**
     * Get the agenda item record associated with the decision.
     *
     * @return BelongsTo
     */
    public function agendaItem()
    {
        return $this->belongsTo(AgendaItem::class);
    }
}
