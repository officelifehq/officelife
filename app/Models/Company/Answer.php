<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use LogsActivity,
        HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'question_id',
        'employee_id',
        'body',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'body',
    ];

    /**
     * Get the question record associated with the answer.
     *
     * @return BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Get the employee record associated with the answer.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
