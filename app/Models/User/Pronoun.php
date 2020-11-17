<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Represent a gender identity.
 */
class Pronoun extends Model
{
    use HasFactory;

    protected $table = 'pronouns';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'label',
        'translation_key',
    ];

    /**
     * Get the complete label of the pronoun.
     *
     * @param string $value
     *
     * @return string
     */
    public function getLabelAttribute($value): string
    {
        return trans($this->translation_key);
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
            'label' => $this->label,
        ];
    }
}
