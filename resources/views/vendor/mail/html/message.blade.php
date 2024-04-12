@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="https://dgtournaments.com/images/dgt-logo-red-small.png" alt="DG Tournaments Logo" width="40" class="logo">
            <br />
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @if (isset($subcopy))
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endif

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <a href="https://twitter.com/dgtournaments1">Twitter</a> | <a href="https://instagram.com/dgtournaments">Instagram</a> | <a href="https://facebook.com/dgtournaments">Facebook</a><br>
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        @endcomponent
    @endslot
@endcomponent
