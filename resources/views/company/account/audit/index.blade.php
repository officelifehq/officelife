@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="cf mt4 mw7 center br3 mb3 bg-white box">
    <div class="pa3">
      @foreach ($logs as $log)
        @include('company.account.audit.partials.'.$log->action, ['log' => $log])
      @endforeach
    </div>
  </div>
</div>
@endsection
