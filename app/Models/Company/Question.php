<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use LogsActivity,
        HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'title',
        'activated_at',
        'deactivated_at',
        'active',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'title',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'activated_at',
        'deactivated_at',
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
     * Get the company record associated with the question.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the answer records associated with the question.
     *
     * @return HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class)->orderBy('created_at', 'desc');
    }

    /**
     * Limit results to active questions.
     *
     * @param  Builder $query
     *
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query->where('active', true);
    }

    /**
     * Transform the object to an array representing this object.
     *
     * @return array
     */
    public function toObject(): array
    {
        $numberOfAnswers = Answer::where('question_id', $this->id)->count();

        return [
            'id' => $this->id,
            'company' => [
                'id' => $this->company_id,
            ],
            'title' => $this->title,
            'active' => $this->active,
            'number_of_answers' => $numberOfAnswers,
            'url' => route('company.questions.show', [
                'company' => $this->company,
                'question' => $this,
            ]),
            'created_at' => $this->created_at,
        ];
    }
}
