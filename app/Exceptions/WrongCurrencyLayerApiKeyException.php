<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class WrongCurrencyLayerApiKeyException extends Exception
{
    /**
     * Report or log an exception.
     */
    public function report()
    {
        Log::debug('Wrong API key for Currency Layer');
    }
}
