@extends('partials.app')

@section('content')
  <div class="ph2 ph0-ns">
    <div class="mt4 mt5-l mw7 center section-btn relative">
      <p><span class="pr2">All the companies you are part of</span> <a href="/company/create" class="btn-primary absolute db-l dn br3 pv2 ph3 white no-underline bb-0">Create a company</a></p>
    </div>
    <div class="cf mt4 mw7 center">
      @foreach ($employees as $employee)
        @if ($loop->iteration % 3 == 0)
        <div class="fl w-100 w-25-m w-third-l">
        @else
        <div class="fl w-100 w-25-m w-third-l pr2">
        @endif
          <a href="{{ $employee->company->id }}/dashboard">
            <div class="br3 mb3 bg-white box pa3 home-index-company b relative">
              {{ $employee->company->name }}
              <span class="absolute normal f6">{{ $employee->company->employees()->count() }} employees</span>
            </div>
          </a>
        </div>
      @endforeach
    </div>
    <div class="w-100 dn-ns db mt2">
      <a href="/company/create" class="btn-primary br3 pa3 white no-underline bb-0 db tc">Create a company</a>
    </div>
  </div>
@endsection
