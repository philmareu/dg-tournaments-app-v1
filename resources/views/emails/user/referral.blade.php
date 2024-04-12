@component('mail::message')
# Welcome!

Your friend {{ $referral->referredBy->name }} thought you would enjoy using DG Tournaments! It's a great way to find disc golf tournaments and related information. (Make sure to thank your friend for thinking of you. Maybe buy them a shiny new mini?)

@component('mail::button', ['url' => url('invite/' . $referral->code)])
Accept Invite
@endcomponent

@include('emails.partials.signature')
@endcomponent
