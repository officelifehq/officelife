@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="cf mt4 mw7 center br3 mb3 bg-white box">
    <div class="fn fl-ns w-50-ns pa3">
      Add employee

      <form method="POST" action="{{ tenant('/account/employees') }}">
        {{ csrf_field() }}

        @include('partials.errors')

        {{-- First name --}}
        <div class="">
          <label class="db fw4 lh-copy f6" for="firstname">{{ trans('employee.create_add_firstname') }}</label>
          <input type="text" name="firstname" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" value="{{ old('firstname') }}" required>
        </div>

        {{-- Last name --}}
        <div class="">
          <label class="db fw4 lh-copy f6" for="lastname">{{ trans('employee.create_add_lastname') }}</label>
          <input type="text" name="lastname" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" value="{{ old('lastname') }}" required>
        </div>

        {{-- Permission level --}}
        <div class="">
          <p>What can this person do?</p>

          @if (auth()->user()->permission_level <= config('homas.authorizations.administrator'))
          <div class="db">
            <input type="radio" class="mr1" name="permission_level" id="administrator" value="{{ config('homas.authorizations.administrator') }}">
            <label for="administrator" class="pointer">Administrator</label>
            <p>Can do everything, including account management.</p>
          </div>
          @endif

          <div class="db">
            <input type="radio" class="mr1" name="permission_level" id="hr" value="{{ config('homas.authorizations.hr') }}">
            <label for="hr" class="pointer">Human Resource Representative</label>
            <p>Have access to most features, including reading and writing private information, but can't manage the account itself.</p>
          </div>

          <div class="db">
            <input type="radio" class="mr1" name="permission_level" id="user" value="{{ config('homas.authorizations.user') }}">
            <label for="user" class="pointer">Employee</label>
            <p>Can see all teams and employees, but can not manage the account or read private information.</p>
          </div>
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
