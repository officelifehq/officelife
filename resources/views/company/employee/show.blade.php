@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="mt4 mw7 center br3 mb3 bg-white box">
    <div class="pa3">
      <h3>{{ $employee->name }}</h3>
      <img src="{{ $employee->avatar }}">
      <a href="">Add position</a>
      <a href="">Add hire date</a>
      <a href="">Edit</a>
    </div>
    <div class="pa3">
      <h3>Teams</h3>
      <ul>
        @foreach ($employee->teams as $team)
        <li>
          <a href="{{ tenant('/teams/'.$team->id) }}">{{ $team->name }} ({{ $team->employees->count() }} members)</a>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection
