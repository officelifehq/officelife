@extends('partials.skeleton')

@section('content')
  <div class="mt4 mw7 center br3 mb3 bg-white box">
    <div class="cf">
      <div class="fn fl-ns w-50-ns pa3">
        Create an account now
      </div>
      <div class="fn fl-ns w-50-ns pa3">
        <form method="POST" action="{{ route('signup.store') }}">
          {{ csrf_field() }}

          @include('partials.errors')

          {{-- Subdomain --}}
          <div class="">
            <label class="db fw4 lh-copy f6" for="subdomain">Choose a domain for your account</label>
            <input type="text" name="subdomain" class="br2 f5 w-100 ba b--black-40 pa2 outline-0">
            <span></span>
          </div>

          {{-- Email --}}
          <div class="">
            <label class="db fw4 lh-copy f6" for="email">Your email address</label>
            <input type="email" name="email" class="br2 f5 w-100 ba b--black-40 pa2 outline-0">
            <p>We'll never spam. You'll receive one email to confirm your email address once you sign up, and won't be added to any nasty email marketing campaigns. No email from a sales team.</p>
          </div>

          {{-- Password --}}
          <div class="">
            <label class="db fw4 lh-copy f6" for="password">Enter a hard-to-guess password</label>
            <input type="password" name="password" class="br2 f5 w-100 ba b--black-40 pa2 outline-0">
          </div>

          {{-- Actions --}}
          <div class="">
            <div class="flex-ns justify-between">
              <div>
                <a href="" class="">{{ trans('app.cancel') }}</a>
              </div>
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
