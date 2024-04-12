<div class="activity">
    <div class="uk-grid uk-grid-small uk-flex uk-flex-middle">
        <div class="uk-width-auto">
            @yield('image')
        </div>
        <div class="uk-width-expand">
            <div class="details uk-text-muted">@yield('summary')</div>
            <div class="timestamp">{{ $activity->created_at->diffForHumans() }}</div>
        </div>
    </div>
    <div class="uk-margin uk-text-small">@yield('details')</div>

    <hr>
</div>