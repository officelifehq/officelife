<?php

namespace App\Models\Company;

use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkFromHome extends Model
{
    use HasFactory;

    protected $table = 'employee_work_from_home';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id',
        'date',
        'work_from_home',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'work_from_home' => 'boolean',
    ];

    /**
     * Get the employee record associated with the employee event.
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
            'date' => $this->date->format('Y-m-d'),
            'localized_date' => DateHelper::formatFullDate($this->date),
        ];
    }
}
