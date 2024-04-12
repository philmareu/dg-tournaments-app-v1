@component('mail::message')
# Hello {{ $user->name }}

Just a quick note. Your PDGA rating has been updated from {{ $old }} to {{ $new }}.

{{ config('app.name') }}

[Adjust your email notification]({{ url('user/profile/edit') }})

Event data &copy; <?php echo date("Y") ?> [PDGA](http://pdga.com)
@endcomponent
