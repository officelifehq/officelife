<?php

namespace App\Models\Company;

use App\Helpers\DateHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Page extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'wiki_id',
        'title',
        'content',
    ];

    /**
     * Get the wiki record associated with the page.
     *
     * @return BelongsTo
     */
    public function wiki()
    {
        return $this->belongsTo(Wiki::class);
    }

    /**
     * Get the page revision records associated with the page.
     *
     * @return HasMany
     */
    public function revisions()
    {
        return $this->hasMany(PageRevision::class);
    }

    /**
     * Get the author (employee) who initially wrote the page.
     * If the author doesn't exist in the system anymore, we simply use the
     * name that was saved in the table instead.
     *
     * @return array|null
     */
    public function getOriginalAuthor(): ?array
    {
        $firstRevision = $this->revisions()->with('employee')->first();

        if (! $firstRevision) {
            return null;
        }

        $name = $firstRevision->employee ?
            $firstRevision->employee_name :
            $firstRevision->employee->name;

        return [
            'name' => $name,
            'created_at' => DateHelper::formatDate($firstRevision->created_at),
        ];
    }

    /**
     * Get the most recent editor (employee) of the page.
     *
     * @return array|null
     */
    public function getMostRecentAuthor(): ?array
    {
        $lastRevision = $this->revisions()->with('employee')->orderByDesc('id')->first();

        if (! $lastRevision) {
            return null;
        }

        $name = $lastRevision->employee ?
            $lastRevision->employee_name :
            $lastRevision->employee->name;

        return [
            'name' => $name,
            'created_at' => DateHelper::formatDate($lastRevision->created_at),
        ];
    }
}
