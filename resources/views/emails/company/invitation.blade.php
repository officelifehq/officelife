@component('mail::message')
# You are invited to join {{ $employee->company->name }}.

An employee of {{ $employee->company->name }} would like you to access Homas, the software used to manage human resources.

If you do already have an account on Homas, you will be able to use your existing account.

@component('mail::button', ['url' => $user->getPathConfirmationLink()])
Join {{ $employee->company->name }}
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
