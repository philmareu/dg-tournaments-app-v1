<ul class="uk-nav uk-nav-default">
    <li class="uk-nav-header">Tools</li>
    <li class="{{ $active == 'dashboard' ? 'uk-active' : '' }}"><a href="{{ route('manage.index') }}">Dashboard</a></li>
    <li class="{{ $active == 'sponsor-library' ? 'uk-active' : '' }}"><a href="{{ route('manage.sponsor-library') }}">Sponsor Library</a></li>
    <li class="{{ $active == 'stripe-accounts' ? 'uk-active' : '' }}"><a href="{{ route('manage.stripe-accounts') }}">Stripe Accounts</a></li>
    <li class="{{ $active == 'submit' ? 'uk-active' : '' }}"><a href="{{ route('manage.submit') }}">Submit Tournament</a></li>
    <li class="uk-nav-divider"></li>
    <li class="uk-nav-header">Tournaments</li>
    @foreach(auth()->user()->managing as $tournament)
        <li class="{{ $active == $tournament->slug ? 'uk-active' : '' }}"><a href="{{ route('manage.tournament.index', $tournament->id) }}">{{ $tournament->name }}</a></li>
    @endforeach
</ul>