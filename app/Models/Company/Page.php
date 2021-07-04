<?php

namespace App\Models\Company;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
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
        'pageviews_counter',
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
     * Get the pageview records associated with the page.
     *
     * @return HasMany
     */
    public function pageviews()
    {
        return $this->hasMany(Pageview::class);
    }

    /**
     * Get the author (employee) who initially wrote the page.
     * If the author doesn't exist in the system anymore, we simply use the
     * name that was saved in the table instead.
     *
     * @param int $imageSize
     * @return array|null
     */
    public function getOriginalAuthor(int $imageSize = null): ?array
    {
        $firstRevision = $this->revisions()->with('employee')->first();
        if (! $firstRevision) {
            return null;
        }

        $employee = $firstRevision->employee;

        $name = $employee ?
            $employee->name :
            $firstRevision->employee_name;

        $image = $employee && $imageSize ?
            ImageHelper::getAvatar($employee, $imageSize) :
            null;

        return [
            'id' => $employee ? $employee->id : null,
            'name' => $name,
            'avatar' => $image,
            'url' => $employee ? route('employees.show', [
                'company' => $employee->company_id,
                'employee' => $employee,
            ]) : null,
            'created_at' => DateHelper::formatDate($firstRevision->created_at),
        ];
    }

    /**
     * Get the most recent editor (employee) of the page.
     *
     * @param int $imageSize
     * @return array|null
     */
    public function getMostRecentAuthor(int $imageSize = null): ?array
    {
        $lastRevision = $this->revisions()->with('employee')->orderByDesc('id')->first();
        if (! $lastRevision) {
            return null;
        }

        $employee = $lastRevision->employee;

        $name = $employee ?
            $employee->name :
            $lastRevision->employee_name;

        $image = $employee && $imageSize ?
            ImageHelper::getAvatar($employee, $imageSize) :
            null;

        return [
            'id' => $employee ? $employee->id : null,
            'name' => $name,
            'avatar' => $image,
            'url' => $employee ? route('employees.show', [
                'company' => $employee->company_id,
                'employee' => $employee,
            ]) : null,
            'created_at' => DateHelper::formatDate($lastRevision->created_at),
        ];
    }
}
