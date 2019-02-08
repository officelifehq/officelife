@extends('partials.app')

@section('content')
  <div class="ph2 ph0-ns">
    <div class="mt4 mw7 center br3 mb3 bg-white box">
      <div class="w-50-ns pa3">
        <p><a href="/company/create">Create a company</a></p>

        <ul>
          @foreach ($employees as $employee)
          <li>
            <a href="{{ $employee->company->id }}/dashboard">{{ $employee->company->name }}</a>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>
@endsection
