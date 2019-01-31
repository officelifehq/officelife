You are logged in

<a href="/logout" dusk="logout-button">Logout</a>

@if (!auth()->user()->account->has_dummy_data)
<a href="/account/dummy">Generate fake data</a>
@else
<a href="/account/dummy">Remove fake data</a>
@endif
