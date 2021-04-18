<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $e
     *
     * @throws \Throwable
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof TokenMismatchException) {
            return Redirect::route('login');
        }

        return parent::render($request, $e);
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $e
     *
     * @throws \Throwable
     */
    public function report(Throwable $e)
    {
        if (config('app.sentry.enabled') && app()->bound('sentry') && $this->shouldReport($e)) {
            app('sentry')->captureException($e); // @codeCoverageIgnore
        }

        parent::report($e);
    }
}
