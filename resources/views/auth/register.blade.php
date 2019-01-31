@extends('partials.skeleton')

@section('content')
  <div class="ph2 ph0-ns">
    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <div class="fn fl-ns w-50-ns pa3">
        {{ trans('auth.register_title') }}
      </div>
      <div class="fn fl-ns w-50-ns pa3">
        <form method="POST" action="{{ route('signup.store') }}">
          {{ csrf_field() }}

          @include('partials.errors')

          {{-- Subdomain --}}
          <div class="relative">
            <label class="db fw4 lh-copy f6" for="subdomain">{{ trans('auth.register_domain') }}</label>
            <input type="text" name="subdomain" class="br2 f5 w-100 ba b--black-40 pa2 outline-0 pl5" value="{{ old('subdomain') }}" required>
            <span class="absolute register-https">https://</span>
            <span class="absolute register-domain">.{{ preg_replace('#^https?://#', '', secure_url('/')) }}</span>
            <p class="f7 mb4 lh-title">{{ trans('auth.register_domain_help') }}</p>
          </div>

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
                <button class="btn add w-auto-ns w-100 mb2 pv2 ph3" name="save" type="submit" dusk="signup-button">{{ trans('auth.register_cta') }}</button>
              </div>
            </div>
          </div>
      </form>
      </div>
    </div>
  </div>
@endsection
