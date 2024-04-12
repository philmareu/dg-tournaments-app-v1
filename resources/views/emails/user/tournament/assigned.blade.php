@component('mail::message')
# New Tournament

The tournament "{{ $tournament->name }}" has just been imported and you have permission to manage it in DG Tournaments.

@component('mail::button', ['url' => url('manage/' . $tournament->id)])
    Manage Tournament
@endcomponent

{{ config('app.name') }}
@endcomponent