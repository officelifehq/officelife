<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Morale extends Model
{
    use LogsActivity,
        HasFactory;

    protected $table = 'morale';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'emotion',
        'comment',
        'created_at',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'emotion',
        'comment',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * Get the employee record associated with the morale.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Returns the emotion in a readable format.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getTranslatedEmotionAttribute($value): string
    {
        return trans('account.morale_'.$this->emotion);
    }

    /**
     * Returns the emotion in a readable format.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getEmojiAttribute($value)
    {
        $emoji = '';

        if ($this->emotion == 1) {
            $emoji = '😡 '.trans('dashboard.morale_emotion_bad');
        }
        if ($this->emotion == 2) {
            $emoji = '😌 '.trans('dashboard.morale_emotion_normal');
        }
        if ($this->emotion == 3) {
            $emoji = '🥳 '.trans('dashboard.morale_emotion_good');
        }

        return $emoji;
    }
}
