@component('mail::message')
# Tournament Submitted

You've successfully submitted your disc golf tournament ({{ $tournament->name }}).

@component('mail::button', ['url' => url('manage/' . $tournament->id)])
    Manage Tournament
@endcomponent

{{ config('app.name') }}
@endcomponent