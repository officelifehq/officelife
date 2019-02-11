@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="cf mt4 mw7 center br3 mb3 bg-white box">
    <div class="pa3">
      <ul>
        <li><a href="{{ tenant('/account/teams/create') }}">Add team</a></li>
        @foreach ($teams as $team)
          <li>
            <ul class="list">
              <li class="di"><a href="{{ tenant('/teams/'.$team->id) }}">{{ $team->name }}</a></li>
              <li class="di"><a href="{{ tenant('/account/teams/'.$team->id.'/destroy') }}">Delete</a></li>
            </ul>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection
