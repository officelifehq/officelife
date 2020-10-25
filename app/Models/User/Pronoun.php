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
     *
     * @return array|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function getLabelAttribute($value)
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
