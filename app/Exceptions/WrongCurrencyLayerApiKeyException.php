<?php

namespace App\Exceptions;

use Exception;

class WrongCurrencyLayerApiKeyException extends Exception
{
    /**
     * Report or log an exception.
     */
    public function report()
    {
        \Log::debug('Wrong API key for Currency Layer');
    }
}
