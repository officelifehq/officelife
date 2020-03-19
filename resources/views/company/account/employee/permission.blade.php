@extends('partials.app')

@section('content')
<div class="ph2 ph0-ns">
  <div class="cf mt4 mw7 center br3 mb3 bg-white box">
    <div class="pa3">
      <form method="POST" action="{{ tenant('/account/employees/'.$employee->id.'/permissions') }}">
        {{ csrf_field() }}

        @include('partials.errors')

        Currently, {{ $employee->name }} has the {{ $employee->getPermissionLevel() }} role. You can change this by choosing another permission below.

        {{-- Permission level --}}
        <div class="">
          <p>{{ trans('employee.new_permission_level') }}</p>

          @if (auth()->user()->permission_level <= config('officelife.permission_level.administrator'))
          <div class="db">
            <input type="radio" class="mr1" name="permission_level" id="administrator" value="{{ config('officelife.permission_level.administrator') }}">
            <label for="administrator" class="pointer">{{ trans('employee.new_administrator') }}</label>
            <p>{{ trans('employee.new_administrator_desc') }}</p>
          </div>
          @endif

          <div class="db">
            <input type="radio" class="mr1" name="permission_level" id="hr" value="{{ config('officelife.permission_level.hr') }}">
            <label for="hr" class="pointer">{{ trans('employee.new_hr') }}</label>
            <p>{{ trans('employee.new_hr_desc') }}</p>
          </div>

          <div class="db">
            <input type="radio" class="mr1" name="permission_level" id="user" value="{{ config('officelife.permission_level.user') }}">
            <label for="user" class="pointer">{{ trans('employee.new_user') }}</label>
            <p>{{ trans('employee.new_user_desc') }}</p>
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
