<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if (! app()->environment('local') && in_array($response->getStatusCode(), [500, 503, 401, 404, 403])) {
            return Inertia::render('Error', ['status' => $response->getStatusCode()])
                ->toResponse($request)
                ->setStatusCode($response->getStatusCode());
        } elseif ($response->getStatusCode() === 419) {
            return back()->with([
                'message' => trans('errors.message_419'),
            ]);
        }

        return $response;
    }

    /**
     * Report or log an exception.
     *
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
