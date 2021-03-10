<?php

namespace App\Models\Company;

use App\Helpers\NotificationHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'action',
        'objects',
        'read',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'read' => 'boolean',
    ];

    /**
     * Get the employee record associated with the notification.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the JSON object.
     *
     * @param mixed $value
     *
     * @return array
     */
    public function getObjectAttribute($value)
    {
        return json_decode($this->objects);
    }

    /**
     * Get the content of the notification, if defined.
     *
     * @param mixed $value
     *
     * @return string
     */
    public function getContentAttribute($value): string
    {
        return NotificationHelper::process($this);
    }
}
