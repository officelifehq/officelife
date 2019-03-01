<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="turbolinks-cache-control" content="no-cache">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('/js/manifest.js') }}" defer></script>
    <script src="{{ mix('/js/vendor.js') }}" defer></script>
    <script src="{{ mix('/js/app.js') }}" defer></script>

    <title>@yield('title', trans('app.application_title'))</title>
  </head>
  <body data-account-id={{ Auth::check() ? auth()->user()->account_id : 0 }}>

    <div id="app" data-component="{{ $name }}" data-props="{{ json_encode($data) }}"></div>

    @stack('scripts')

  </body>
</html>
