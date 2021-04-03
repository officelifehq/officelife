<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OneOnOneEntry extends Model
{
    use LogsActivity,
        HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'one_on_one_entries';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'manager_id',
        'employee_id',
        'happened_at',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'happened_at',
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
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $casts = [
        'happened' => 'boolean',
    ];

    /**
     * Get the manager record associated with the entry.
     *
     * @return BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    /**
     * Get the employee record associated with the entry.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get the one on one talking point associated with the entry.
     *
     * @return HasMany
     */
    public function talkingPoints()
    {
        return $this->hasMany(OneOnOneTalkingPoint::class, 'one_on_one_entry_id');
    }

    /**
     * Get the one on one action items associated with the entry.
     *
     * @return HasMany
     */
    public function actionItems()
    {
        return $this->hasMany(OneOnOneActionItem::class, 'one_on_one_entry_id');
    }

    /**
     * Get the one on one action items associated with the entry.
     *
     * @return HasMany
     */
    public function notes()
    {
        return $this->hasMany(OneOnOneNote::class, 'one_on_one_entry_id');
    }
}
