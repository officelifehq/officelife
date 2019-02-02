As an HR representative, you can...

<ul>
  <li><a href="{{ tenant('/account/employees') }}">Add/remove employees</a></li>
</ul>

<h2>Teams you are part of</h2>

<h2>All the teams</h2>

@foreach($teams as $team)
<a href="{{ tenant('/teams/'.$team->id) }}">{{ $team->name }}</a>
{{ $team->users->count() }} members
@endforeach
