@component('mail::message')

You have submitted a claim to manage the tournament page for {{ $tournament->name }} ({{ $tournament->dateSpan->formattedDate() }}). We have submitted the claim for approval. Once the claim has been approved, we will send you confirmation and you can make updates to the tournament page.
If you have questions or have issues, please contact us at admin@dgtournaments.com.

@include('emails.partials.signature')
@endcomponent
