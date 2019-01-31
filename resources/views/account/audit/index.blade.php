@extends('partials.app')

@section('content')
  @foreach ($logs as $log)
    {{ $log->id }}
  @endforeach
@endsection
