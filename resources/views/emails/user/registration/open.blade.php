@component('mail::message')
# Registration Open

Registration has opened for the <a href="{{ $registration->tournament->path }}">{{ $registration->tournament->name }}</a>.

@if($registration->url != "")
@component('mail::button', ['url' => $registration->url])
Register
@endcomponent
@endif

Enjoy,<br>
{{ config('app.name') }}

You can disable these email notifications in your <a href="{{ route('account.notifications') }}">notification settings</a>.
@endcomponent