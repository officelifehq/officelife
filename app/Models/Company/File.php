<?php

namespace App\Models\Company;

use App\Events\FileDeleted;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'uuid',
        'uploader_employee_id',
        'uploader_name',
        'original_url',
        'cdn_url',
        'name',
        'mime_type',
        'type',
        'size',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'deleted' => FileDeleted::class,
    ];

    /**
     * Get the company record associated with the file object.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the employee record associated with the file object.
     *
     * @return BelongsTo
     */
    public function uploader()
    {
        return $this->belongsTo(Employee::class, 'uploader_employee_id');
    }
}
