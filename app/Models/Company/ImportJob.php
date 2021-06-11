<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportJob extends Model
{
    use HasFactory;

    protected $table = 'import_jobs';

    /**
     * Possible status of an import job.
     */
    const CREATED = 'created';
    const STARTED = 'started';
    const UPLOADED = 'uploaded';
    const IMPORTING = 'importing';
    const IMPORTED = 'imported';
    const FAILED = 'failed';

    /**
     * Possible skip reasons.
     */
    const INVALID_EMAIL = 'invalid_email';
    const EMAIL_ALREADY_TAKEN = 'email_already_taken';

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
        'import_started_at',
        'import_ended_at',
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
     * Get the company record associated with the import job object.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Get the import job records associated with the import job.
     *
     * @return HasMany
     */
    public function reports()
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
