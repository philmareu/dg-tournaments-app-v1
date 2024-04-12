@component('mail::message')
# Hello

Your PDGA rating has been updated from {{ $old }} to {{ $new }}.

@component('mail::button', ['url' => route('account.notifications')])
Update Notification Settings
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
