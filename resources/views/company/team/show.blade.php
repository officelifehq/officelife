@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="mt4 mw7 center br3 mb3 bg-white box">
    <div class="pa3">
      <h3>{{ $team->name }}</h3>
      <a href="">Edit</a>
    </div>
    <div class="pa3">
      <h3>Employees</h3>
      <ul>
        @foreach ($team->employees as $employee)
        <li>
          <a href="{{ tenant('/employees/'.$employee->id) }}">{{ $employee->name }}</a>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection
