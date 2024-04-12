<header class="uk-text-center uk-margin uk-padding-small">

    <div class="uk-margin uk-text-center">
        <span uk-icon="icon: dgt; ratio: .5;"></span>
        <h2>DG Tournaments</h2>
    </div>

    <div class="uk-grid uk-grid-small">
        <div class="uk-width-1-2">
            <a href="{{ route('manage.index') }}" class="uk-button uk-button-default uk-button-small uk-width-1-1">Exit</a>
        </div>
        <div class="uk-width-1-2">
            <a href="{{ url($tournament->path) }}" class="uk-button uk-button-default uk-button-small uk-width-1-1">View</a>
        </div>
    </div>

    {{--<img src="{{ url('images/small/' . $tournament->poster->filename) }}" alt="Tournament poster" class="uk-width-1-3 uk-margin">--}}
    {{--<div class="side-title uk-margin">{{ $tournament->name }}</div>--}}
    {{--<a href="{{ url($tournament->path) }}" class="uk-button uk-button-default uk-button-small">View</a>--}}
</header>

{{--<hr>--}}

<ul class="uk-nav uk-nav-default">
{{--    <li class="{{ $active == 'dashboard' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.index', $tournament->id) }}">Dashboard</a></li>--}}

    <li class="uk-nav-header"><span uk-icon="icon: info;" class="uk-margin-small-right"></span>Information</li>
    <li class="{{ $active == 'basics' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.basics', $tournament->id) }}">Basics</a></li>
    <li class="{{ $active == 'registration' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.registration', $tournament->id) }}">Registration</a></li>
    <li class="{{ $active == 'schedule' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.schedule', $tournament->id) }}">Schedule</a></li>
    <li class="{{ $active == 'links' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.links', $tournament->id) }}">Links</a></li>
    <li class="{{ $active == 'media' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.media', $tournament->id) }}">Media</a></li>
    <li class="{{ $active == 'player-packs' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.player-packs', $tournament->id) }}">Players Packs</a></li>
</ul>

<div class="uk-margin">
    <course-nav></course-nav>
</div>

<div class="uk-margin">
    <sponsorship-nav></sponsorship-nav>
</div>

<ul class="uk-nav uk-nav-default">
    <li class="uk-nav-header"><span uk-icon="icon: file-edit;" class="uk-margin-small-right"></span>Orders</li>
    <li class="{{ $active == 'orders' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.orders', $tournament->id) }}">Orders</a></li>

    <li class="uk-nav-header"><span uk-icon="icon: cog;" class="uk-margin-small-right"></span>Settings</li>
    <li class="{{ $active == 'credit-card' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.credit-card', $tournament->id) }}">Credit Card Setup</a></li>

    <li class="uk-nav-header"><span uk-icon="icon: users;" class="uk-margin-small-right"></span>People</li>
{{--    <li class="{{ $active == 'followers' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.followers', $tournament->id) }}">Staff</a></li>--}}
{{--    <li class="{{ $active == 'followers' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.followers', $tournament->id) }}">Players</a></li>--}}
    <li class="{{ $active == 'followers' ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.followers', $tournament->id) }}">Followers</a></li>
</ul>