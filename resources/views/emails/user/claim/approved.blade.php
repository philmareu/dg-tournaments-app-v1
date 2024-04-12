@component('mail::message')

# Approved!

Your claim to manage the tournament page <a href="{{ url($tournament->path) }}">{{ $tournament->tournament_name }}</a> has been approved. Make sure you are logged into
<a href="https://dgtournaments.com">dgtournaments.com</a> to make updates to the tournament page.

If you have any questions, feedback, or issues, please do not hesitate to contact us at admin@dgtournaments.com.

@include('emails.partials.signature')
@endcomponent
