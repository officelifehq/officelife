<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Morale extends Model
{
    use LogsActivity;

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
        'is_dummy',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_dummy' => 'boolean',
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
     * @return string
     * @param mixed $value
     */
    public function getTranslatedEmotionAttribute($value)
    {
        return trans('account.morale_'.$this->emotion);
    }

    /**
     * Returns the emotion in a readable format.
     *
     * @return string
     * @param mixed $value
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
