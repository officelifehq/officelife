<?php

namespace App\Models\User;

use Illuminate\Database\Eloquent\Model;

/**
 * Represent a gender identity.
 */
class Pronoun extends Model
{
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
     * @return string
     */
    public function getLabelAttribute($value): string
    {
        return trans($this->translation_key);
    }
}
