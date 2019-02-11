@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="cf mt4 mw7 center br3 mb3 bg-white box">
    <div class="pa3">
      <ul>
        <li><a href="{{ tenant('/account/employees/create') }}">Add employee</a></li>
        @foreach ($employees as $employee)
          @if ($employee->user_id === auth()->user()->id)
          <li><a href="{{ tenant('/employees/'.$employee->id) }}">{{ $employee->name }} ({{ $employee->getPermissionLevel() }})</a> <span>That's you</span></li>
          @else
          <li>
            <ul class="">
              <li class="di"><a href="{{ tenant('/employees/'.$employee->id) }}">{{ $employee->name }} ({{ $employee->getPermissionLevel() }})</a></li>
              <li class="di"><a href="{{ tenant('/account/employees/'.$employee->id.'/permissions') }}">Change permission</a></li>
              <li class="di"><a href="{{ tenant('/employees/'.$employee->id.'/lock') }}">Lock account</a></li>
              <li class="di"><a href="{{ tenant('/account/employees/'.$employee->id.'/destroy') }}">Delete</a></li>
            </ul>
          </li>
          @endif
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection
