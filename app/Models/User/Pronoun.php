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
     * Possible status of a pronoun.
     */
    const HE = 'he/him';
    const SHE = 'she/her';
    const THEY = 'they/them';
    const PER = 'per/per';
    const VE = 've/ver';
    const XE = 'xe/xem';
    const ZE = 'ze/hir';

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
}
