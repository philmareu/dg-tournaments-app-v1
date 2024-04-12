@component('mail::message')
# Welcome

Thanks for signing up for dgtournaments.com, the disc golf tournament management platform for players, directors and sponsors. Please activate your account using the link below.

@component('mail::button', ['url' => $link])
Activate Account
@endcomponent

@include('emails.partials.signature')
@endcomponent
