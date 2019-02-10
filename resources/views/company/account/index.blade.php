@extends('partials.app')

@section('content')
  <div class="ph2 ph0-ns">
    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <div class="fn fl-ns w-50-ns pa3">
        @include('company.account._administrator')
        @include('company.account._hr')
      </div>
    </div>
  </div>
@endsection
