<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'author_id',
        'author_name',
        'content',
        'commentable_id',
        'commentable_type',
    ];

    /**
     * Get the Company record associated with the comment object.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the parent commentable model.
     *
     * @return MorphTo
     */
    public function commentable()
    {
        return $this->morphTo();
    }

    /**
     * Get the employee record associated with the comment object.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }
}
