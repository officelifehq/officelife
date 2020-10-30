<!DOCTYPE html>
<html lang="{{ \App::getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <base href="{{ url('/') }}/" />
  <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
  <script src="{{ asset(mix('js/app.js')) }}" defer></script>
  <title>@yield('title', config('app.name'))</title>

  @routes
</head>

<body>

  @inertia

</body>

</html>
