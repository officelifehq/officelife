<!-- Confirm account banner -->
@if (!auth()->user()->account->confirmed)
<p>You need to confirm your email address before adding users.</p>
@endif
