<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IssueType extends Model
{
    use HasFactory;

    protected $table = 'issue_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'icon_hex_color',
    ];

    /**
     * Get the company record associated with the issue type.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the issue records associated with the issue type.
     *
     * @return HasMany
     */
    public function issues()
    {
        return $this->hasMany(ProjectIssue::class);
    }
}
