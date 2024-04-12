@component('mail::message')

# Hey there

{{ $tournament->claimRequest->user->name }} with an email address of {{ $tournament->claimRequest->user->email }} has requested to manage your tournament for {{ $tournament->name }} - {{ $tournament->dateSpan->formattedDate() }} on dgtournaments.com. If you would like to approve this claim, please click the "Approve Claim" link to complete this process. If you do not want to approve this claim, simply ignore this email.

@component('mail::button', ['url' => url('tournament/claim/confirm/' . $tournament->claimRequest->token)])
Approve Claim
@endcomponent

Please email us at admin@dgtournaments.com if you have any questions or issues.

@include('emails.partials.signature')
@endcomponent
