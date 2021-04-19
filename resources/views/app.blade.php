<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <base href="{{ url('/') }}/" />
  <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
  <script src="{{ asset(mix('js/app.js')) }}" defer></script>
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon.png') }}" />
  <title>@yield('title', config('app.name'))</title>

  @if (config('app.sentry.enabled'))
  <script>
    const SentryConfig = {!! \json_encode([
      'dsn' => config('sentry.dsn'),
      'environment' => config('sentry.environment'),
      'sendDefaultPii' => config('sentry.send_default_pii'),
      'tracesSampleRate' => config('sentry.traces_sample_rate'),
    ]); !!}
  </script>
  @endif

  @routes
</head>

<body>

  @inertia

</body>

</html>
