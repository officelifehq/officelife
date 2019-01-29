@component('mail::message')
# Please click the big button below.

Thanks for signing up. There is just one more thing to do: we need you to
confirm that you indeed own the email address used to subscribe to Homas.
We apologize for having to do this, but... Internet.

@component('mail::button', ['url' => $account->getPathConfirmationLink()])
Confirm your email address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
