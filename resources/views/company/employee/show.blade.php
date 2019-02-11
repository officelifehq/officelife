@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="mt4 mw7 center br3 mb3 bg-white box">
    <div class="pa3">
      <h3>{{ $employee->name }}</h3>
      <a href="">Add position</a>
      <a href="">Add hire date</a>
    </div>
  </div>
</div>
@endsection
