<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class DisciplineEvent extends Model
{
    use HasFactory;

    protected $table = 'discipline_events';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'discipline_case_id',
        'author_id',
        'author_name',
        'happened_at',
        'description',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'happened_at',
    ];

    /**
     * Get the company record associated with the discipline event.
     *
     * @return BelongsTo
     */
    public function case()
    {
        return $this->belongsTo(DisciplineCase::class, 'discipline_case_id');
    }

    /**
     * Get the employee record associated with the discipline event.
     *
     * @return BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
    }

    /**
     * Get the file entries associated with the discipline event.
     *
     * @return BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'discipline_event_file', 'discipline_event_id', 'file_id');
    }
}
