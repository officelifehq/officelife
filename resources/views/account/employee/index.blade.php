@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="cf mt4 mw7 center br3 mb3 bg-white box">
    <div class="fn fl-ns w-50-ns pa3">
      @foreach ($employees as $employee)
        {{ $employee->name }}
      @endforeach
    </div>
  </div>
</div>
@endsection
