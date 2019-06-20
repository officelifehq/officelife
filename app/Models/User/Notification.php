<?php

namespace App\Models\User;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'action',
        'content',
        'read',
        'is_dummy',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'read' => 'boolean',
        'is_dummy' => 'boolean',
    ];

    /**
     * Get the user record associated with the notification.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company record associated with the notification.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
