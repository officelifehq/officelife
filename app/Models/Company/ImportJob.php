<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportJob extends Model
{
    use HasFactory;

    protected $table = 'import_jobs';

    /**
     * Possible status of an expense.
     */
    const CREATED = 'created';
    const IMPORTED = 'imported';
    const FAILED = 'failed';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'author_id',
        'author_name',
        'status',
        'employees_found_count',
        'employees_skipped_count',
        'employees_imported_count',
        'filename',
        'import_started_at',
        'import_ended_at',
        'imported',
        'failed',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'failed' => 'boolean',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'import_started_at',
        'import_ended_at',
    ];

    /**
     * Get the company record associated with the import job object.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the import job records associated with the import job.
     *
     * @return HasMany
     */
    public function importJobReports()
    {
        return $this->hasMany(ImportJobReport::class);
    }

    /**
     * Get the file attached to the import job.
     */
    public function file()
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
