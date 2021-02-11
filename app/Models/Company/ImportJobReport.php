<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ImportJobReport extends Model
{
    use HasFactory;

    protected $table = 'import_job_reports';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'import_job_id',
        'employee_first_name',
        'employee_last_name',
        'employee_email',
        'skipped_during_upload',
        'skipped_during_upload_reason',
        'skipped_during_import',
        'skipped_during_import_reason',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'skipped_during_upload' => 'boolean',
        'skipped_during_import' => 'boolean',
    ];

    /**
     * Get the import job associated with the import job report object.
     *
     * @return BelongsTo
     */
    public function importJob()
    {
        return $this->belongsTo(ImportJob::class, 'import_job_id', 'id');
    }
}
