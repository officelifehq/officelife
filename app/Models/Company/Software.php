<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Software extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'softwares';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'product_key',
        'seats',
        'website',
        'licensed_to_name',
        'licensed_to_email_address',
        'order_number',
        'purchase_amount',
        'currency',
        'converted_purchase_amount',
        'converted_to_currency',
        'converted_at',
        'exchange_rate',
        'purchased_at',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'purchased_at',
        'converted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'seats' => 'integer',
    ];

    /**
     * Get the company record associated with the software.
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the e-coffee match records associated with the software.
     *
     * @return BelongsToMany
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class)->withTimestamps();
    }

    /**
     * Get the file entries associated with the software.
     *
     * @return BelongsToMany
     */
    public function files()
    {
        return $this->belongsToMany(File::class);
    }
}
