<?php

namespace App\Models\Company;

use App\Helpers\LogHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'author_id',
        'author_name',
        'action',
        'objects',
        'audited_at',
        'ip_address',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'audited_at',
    ];

    /**
     * Get the company record associated with the audit log.

     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the Employee record associated with the audit log.

     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the JSON object.
     *
     * @param mixed $value

     * @return mixed
     */
    public function getObjectAttribute($value)
    {
        return json_decode($this->objects);
    }

    /**
     * Get the content of the audit log, if defined.
     *
     * @param mixed $value

     * @return string
     */
    public function getContentAttribute($value): string
    {
        return LogHelper::processAuditLog($this);
    }
}
