@component('mail::message')
# You are invited to join {{ $employee->company->name }}.

An employee of {{ $employee->company->name }} would like you to join them on Homas, the software used to manage human resources.

If you do already have an account on Homas, you will be able to use your existing account.

@component('mail::button', ['url' => $employee->getPathInvitationLink()])
Join {{ $employee->company->name }} on Homas
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
