<?php

namespace App\Models\Company;

use App\Helpers\DateHelper;
use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Worklog extends Model
{
    use LogsActivity,
        HasFactory;

    protected $table = 'worklogs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'content',
        'created_at',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'content',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
    ];

    /**
     * Get the employee records associated with the worklog.
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Transform the object to an array representing this object.
     *
     * @return array
     */
    public function toObject(): array
    {
        return [
            'id' => $this->id,
            'employee' => [
                'id' => $this->employee->id,
                'name' => $this->employee->name,
            ],
            'content' => $this->content,
            'parsed_content' => StringHelper::parse($this->content),
            'localized_created_at' => DateHelper::formatDate($this->created_at),
            'created_at' => $this->created_at,
        ];
    }
}
