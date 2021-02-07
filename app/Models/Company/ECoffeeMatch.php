<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ECoffeeMatch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'e_coffee_matches';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'e_coffee_id',
        'employee_id',
        'with_employee_id',
        'happened',
        'created_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'happened' => 'boolean',
    ];

    /**
     * Get the e-coffee record associated with the e-coffee match.
     *
     * @return BelongsTo
     */
    public function eCoffee()
    {
        return $this->belongsTo(ECoffee::class);
    }

    /**
     * Get the employee records associated with the e-coffee match.
     *
     * @return belongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get the employee matched with records associated with the e-coffee match.
     *
     * @return belongsTo
     */
    public function employeeMatchedWith()
    {
        return $this->belongsTo(Employee::class, 'with_employee_id');
    }
}
