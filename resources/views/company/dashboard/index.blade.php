@extends('partials.app')

@section('content')
  <div class="ph2 ph0-ns">
    <div class="cf mt4 mw7 center">
      <h2 class="tc">{{ $company->name }}</h2>
    </div>
    <div class="cf mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <ul>
          <li><a href="{{ tenant('/account') }}">Access Adminland</a></li>
          <li>latest news</li>
          <li>hr: expense overview</li>
          <li>hr: view all teams</li>
          <li>view company morale</li>
          <li>view all employees</li>
          <li>menu de la semaine</li>
        </ul>
      </div>
    </div>

    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <h2>Team</h2>
        <ul>
          <li>team agenda</li>
          <li>anniversaires</li>
          <li>latest news</li>
          <li>manager: view time off requests</li>
          <li>manager: view morale</li>
          <li>manager: expense approval</li>
          <li>manager: one on one</li>
        </ul>
      </div>
    </div>

    <div class="cf mt4 mw7 center br3 mb3 bg-white box">
      <div class="pa3">
        <h2>Me</h2>
        <ul>
          <li>View holidays</li>
          <li>Book time off</li>
          <li>Log morale</li>
          <li>Reply to what you've done this week</li>
          <li>Log an expense</li>
          <li>View one on ones</li>
        </ul>
      </div>
    </div>
  </div>
@endsection
