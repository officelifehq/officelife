<!-- Confirm account banner -->
@if (!auth()->user()->email_verified_at)
<p>You need to confirm your email address before getting access to all features.</p>
@endif

You are logged in as {{ auth()->user()->name }}
<a href="/dashboard">Back to user dashboard</a>
<a href="/logout" data-cy="logout-button">Logout</a>
