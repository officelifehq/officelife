@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="cf mt4 mw7 center br3 mb3 bg-white box">
    <div class="fn fl-ns w-50-ns pa3">
      Create a new company

      <form method="POST" action="/company">
        {{ csrf_field() }}

        @include('partials.errors')

        {{-- Name --}}
        <div class="">
          <label class="db fw4 lh-copy f6" for="name">{{ trans('company.new_name') }}</label>
          <input type="text" name="name" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" value="{{ old('name') }}" required>
        </div>

        {{-- Actions --}}
        <div class="">
          <div class="flex-ns justify-between">
            <div>
              <button class="btn add w-auto-ns w-100 mb2 pv2 ph3" name="save" type="submit">{{ trans('app.save') }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
