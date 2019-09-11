<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyNews extends Model
{
    use LogsActivity;

    protected $table = 'company_news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'author_id',
        'author_name',
        'title',
        'content',
        'is_dummy',
        'created_at',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'title',
        'content',
    ];

    /**
     * Get the company record associated with the company news.
     *
     * @return belongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the employee record associated with the company news.
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }
}
