<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <link rel="stylesheet" href="{{ mix('css/app.css') }}" data-turbolinks-track="true">
  <script src="{{ mix('/js/app.js') }}" defer></script>
  <title>@yield('title', config('app.name'))</title>

  <livewire:styles>
</head>

<body data-account-id="{{ Auth::check() ? auth()->user()->account_id : 0 }}" class="bg-gray-300">

  <div class="container mx-auto">
    @yield('content')
  </div>

  <livewire:scripts>
</body>

</html>
