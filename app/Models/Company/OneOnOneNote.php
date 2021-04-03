<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OneOnOneNote extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'one_on_one_notes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'one_on_one_entry_id',
        'note',
    ];

    /**
     * Get the one on one record associated with the note.
     *
     * @return BelongsTo
     */
    public function entry()
    {
        return $this->belongsTo(OneOnOneEntry::class, 'one_on_one_entry_id');
    }
}
