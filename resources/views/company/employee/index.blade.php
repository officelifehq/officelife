@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="cf mt4 mw7 center br3 mb3 bg-white box">
    <div class="fn fl-ns w-50-ns pa3">
      <ul>
        <li><a href="{{ tenant('/account/employees/create') }}">Add employee</a></li>
        @foreach ($employees as $employee)
        <li>{{ $employee->user->name }} ({{ $employee->getPermissionLevel() }})</li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection
