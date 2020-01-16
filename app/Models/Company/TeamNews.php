<?php

namespace App\Models\Company;

use App\Helpers\DateHelper;
use App\Helpers\StringHelper;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeamNews extends Model
{
    use LogsActivity;

    protected $table = 'team_news';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id',
        'author_id',
        'author_name',
        'title',
        'content',
        'is_dummy',
        'created_at',
    ];

    /**
     * The attributes that are logged when changed.
     *
     * @var array
     */
    protected static $logAttributes = [
        'title',
        'content',
    ];

    /**
     * Get the team record associated with the team news.
     *
     * @return belongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Get the employee record associated with the team news.
     */
    public function author()
    {
        return $this->belongsTo(Employee::class, 'author_id');
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
            'company' => [
                'id' => $this->company_id,
            ],
            'title' => $this->title,
            'content' => $this->content,
            'parsed_content' => StringHelper::parse($this->content),
            'author' => [
                'id' => is_null($this->author) ? null : $this->author->id,
                'name' => is_null($this->author) ? $this->author_name : $this->author->name,
                'avatar' => is_null($this->author) ? null : $this->author->avatar,
            ],
            'localized_created_at' => DateHelper::getShortDateWithTime($this->created_at),
            'created_at' => $this->created_at,
        ];
    }
}
