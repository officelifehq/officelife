<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OneOnOneTalkingPoint extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'one_on_one_talking_points';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'one_on_one_entry_id',
        'description',
        'checked',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $casts = [
        'checked' => 'boolean',
    ];

    /**
     * Get the one on one record associated with the talking point.
     *
     * @return BelongsTo
     */
    public function entry()
    {
        return $this->belongsTo(OneOnOneEntry::class, 'one_on_one_entry_id');
    }
}
