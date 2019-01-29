<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <title>@yield('title', trans('app.application_title'))</title>
  </head>
  <body data-account-id={{ Auth::check() ? auth()->user()->account_id : 0 }}>

    <div id="app">

      @yield('content')

    </div>

    <script src="{{ mix('/js/app.js') }}"></script>
    @stack('scripts')

  </body>
</html>
