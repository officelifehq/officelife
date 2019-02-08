@extends('partials.app')

@section('content')
  <div class="ph2 ph0-ns">
    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <div class="fn fl-ns w-50-ns pa3">
        @if (!$company->has_dummy_data)
        <a href="{{ tenant('/account/dummy') }}">Generate fake data</a>
        @else
        <a href="{{ tenant('/account/dummy') }}">Remove fake data</a>
        @endif

        Number of employees: {{ $numberEmployees }}
        <p><a href="/company/create">Create a company</a></p>
      </div>
      @include('dashboard._administrator')
      @include('dashboard._hr')
    </div>
  </div>
@endsection
