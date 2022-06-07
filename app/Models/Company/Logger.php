<?php

namespace App\Models\Company;

use Illuminate\Database\Eloquent\Model;

class Logger extends Model
{
    /**
     * Get the JSON object.
     *
     * @param mixed $value

     * @return mixed
     */
    public function getObjectAttribute($value)
    {
        return json_decode($this->objects);
    }
}
