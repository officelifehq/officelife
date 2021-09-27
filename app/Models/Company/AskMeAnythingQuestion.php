<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AskMeAnythingQuestion extends Model
{
    use HasFactory;

    protected $table = 'ask_me_anything_questions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'ask_me_anything_session_id',
        'employee_id',
        'question',
        'answered',
        'anonymous',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'answered' => 'boolean',
        'anonymous' => 'boolean',
    ];

    /**
     * Get the session record associated with the question.
     *
     * @return BelongsTo
     */
    public function session()
    {
        return $this->belongsTo(AskMeAnythingSession::class, 'ask_me_anything_session_id');
    }

    /**
     * Get the employee record associated with the question.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
