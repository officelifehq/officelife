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
}
