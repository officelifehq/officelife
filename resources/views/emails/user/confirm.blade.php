@component('mail::message')
# Please click the big button below.

Thanks for signing up. There is just one more thing to do: we need you to
confirm that you really own the email address used to subscribe to officelife.
We apologize for having to do this, but... Internet.

@component('mail::button', ['url' => $user->getPathConfirmationLink()])
Confirm your email address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
