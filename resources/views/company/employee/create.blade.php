@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="cf mt4 mw7 center br3 mb3 bg-white box">
    <div class="pa3">
      {{ trans('employee.new_title') }}

      <form method="POST" action="{{ tenant('/account/employees') }}">
        {{ csrf_field() }}

        @include('partials.errors')

        {{-- First name --}}
        <div class="">
          <label class="db fw4 lh-copy f6" for="firstname">{{ trans('employee.new_firstname') }}</label>
          <input type="text" name="firstname" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" value="{{ old('firstname') }}" required>
        </div>

        {{-- Last name --}}
        <div class="">
          <label class="db fw4 lh-copy f6" for="lastname">{{ trans('employee.new_lastname') }}</label>
          <input type="text" name="lastname" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" value="{{ old('lastname') }}" required>
        </div>

        {{-- Email --}}
        <div class="">
          <label class="db fw4 lh-copy f6" for="lastname">{{ trans('employee.new_email') }}</label>
          <input type="text" name="lastname" class="br2 f5 w-100 ba b--black-40 pa2 outline-0" value="{{ old('lastname') }}" required>
        </div>

        {{-- Permission level --}}
        <div class="">
          <p>{{ trans('employee.new_permission_level') }}</p>

          @if (auth()->user()->permission_level <= config('homas.authorizations.administrator'))
          <div class="db">
            <input type="radio" class="mr1" name="permission_level" id="administrator" value="{{ config('homas.authorizations.administrator') }}">
            <label for="administrator" class="pointer">{{ trans('employee.new_administrator') }}</label>
            <p>{{ trans('employee.new_administrator_desc') }}</p>
          </div>
          @endif

          <div class="db">
            <input type="radio" class="mr1" name="permission_level" id="hr" value="{{ config('homas.authorizations.hr') }}">
            <label for="hr" class="pointer">{{ trans('employee.new_hr') }}</label>
            <p>{{ trans('employee.new_hr_desc') }}</p>
          </div>

          <div class="db">
            <input type="radio" class="mr1" name="permission_level" id="user" value="{{ config('homas.authorizations.user') }}">
            <label for="user" class="pointer">{{ trans('employee.new_user') }}</label>
            <p>{{ trans('employee.new_user_desc') }}</p>
          </div>
        </div>

        {{-- Invite user --}}
        <div class="">
          <div class="flex items-center mb2">
            <input class="mr2" type="checkbox" id="" value="">
            <label for="" class="lh-copy">{{ trans('employee.new_send_email') }}</label>
          </div>
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
