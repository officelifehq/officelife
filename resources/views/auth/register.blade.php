@extends('partials.skeleton')

@section('main')
  <div class="ph2 ph0-ns">
    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <div class="fn fl-ns w-50-ns pa3">
        {{ trans('auth.register_title') }}
      </div>
      <div class="fn fl-ns w-50-ns pa3">
        <form method="POST" action="/signup">
          {{ csrf_field() }}

          @include('partials.errors')

          {{-- Email --}}
          <div class="">
            <label class="db fw4 lh-copy f6" for="email">{{ trans('auth.register_email') }}</label>
            <input type="email" name="email" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" value="{{ old('email') }}" required>
            <p class="f7 mb4 lh-title">{{ trans('auth.register_email_help') }}</p>
          </div>

          {{-- Password --}}
          <div class="mb4">
            <label class="db fw4 lh-copy f6" for="password">{{ trans('auth.register_password') }}</label>
            <input type="password" name="password" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" required>
          </div>

          {{-- Actions --}}
          <div class="">
            <div class="flex-ns justify-between">
              <div>
                <button class="btn add w-auto-ns w-100 mb2 pv2 ph3" name="save" type="submit">{{ trans('auth.register_cta') }}</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
