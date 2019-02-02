@extends('partials.app')

@section('content')
  <div class="ph2 ph0-ns">
    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <div class="fn fl-ns w-50-ns pa3">
        You are logged in

        <a href="/logout" data-cy="logout-button">Logout</a>

        @if (!auth()->user()->account->has_dummy_data)
        <a href="/account/dummy">Generate fake data</a>
        @else
        <a href="/account/dummy">Remove fake data</a>
        @endif

        Number of teams: {{ $numberTeams }}

        Number of employees: {{ $numberEmployees }}
      </div>
      @include('dashboard._administrator')
      @include('dashboard._hr')
    </div>
  </div>
@endsection
