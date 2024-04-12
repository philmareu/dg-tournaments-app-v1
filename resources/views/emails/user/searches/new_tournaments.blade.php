@component('mail::message')
# New Tournaments

These tournaments match your saved searches.

@foreach($tournamentNotification['tournaments'] as $tournament)
* [{{ $tournament->name }}, {{ $tournament->location->formatted() }}]({{ url($tournament->path) }})
@endforeach

{{ config('app.name') }}
@endcomponent
