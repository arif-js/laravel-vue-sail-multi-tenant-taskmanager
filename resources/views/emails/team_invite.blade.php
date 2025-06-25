@component('mail::message')
# You have been invited!

You have been invited to join **{{ $teamName }}** as a **{{ $role }}**.

@component('mail::button', ['url' => $acceptUrl])
Accept Invitation
@endcomponent

If you already have an account, please login.  
Otherwise, you can register to accept this invitation.

Thanks,  
{{ config('app.name') }}
@endcomponent
