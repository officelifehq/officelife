<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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
        'fileable_id',
        'fileable_type',
        'filename',
        'hashed_filename',
        'extension',
        'size_in_kb',
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
     * Get the parent imageable model.
     */
    public function fileable()
    {
        return $this->morphTo();
    }

    /**
     * Get the full path of the file.
     *
     * @param mixed $value
     * @return string
     */
    public function getPathAttribute($value): string
    {
        if (config('filesystems.default') == 'local') {
            return storage_path('app').'/'.$this->hashed_filename;
        } else {
            return Storage::url($this->hashed_filename);
        }
    }
}
