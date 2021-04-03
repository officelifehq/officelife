<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuessEmployeeGame extends Model
{
    use HasFactory;

    protected $table = 'guess_employee_games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_who_played_id',
        'employee_to_find_id',
        'first_other_employee_to_find_id',
        'second_other_employee_to_find_id',
        'played',
        'found',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'played' => 'boolean',
        'found' => 'boolean',
    ];

    /**
     * Get the player record associated with the game.
     *
     * @return BelongsTo
     */
    public function player()
    {
        return $this->belongsTo(Employee::class, 'employee_who_played_id');
    }

    /**
     * Get the person to find record associated with the game.
     *
     * @return BelongsTo
     */
    public function employeeToFind()
    {
        return $this->belongsTo(Employee::class, 'employee_to_find_id');
    }

    /**
     * Get the person to find record associated with the game.
     *
     * @return BelongsTo
     */
    public function firstOtherEmployeeToFind()
    {
        return $this->belongsTo(Employee::class, 'first_other_employee_to_find_id');
    }

    /**
     * Get the person to find record associated with the game.
     *
     * @return BelongsTo
     */
    public function secondOtherEmployeeToFind()
    {
        return $this->belongsTo(Employee::class, 'second_other_employee_to_find_id');
    }
}
