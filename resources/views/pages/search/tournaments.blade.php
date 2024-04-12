@extends('layouts.default')

@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.css">
@endpush

@include('partials.mapbox')

@section('meta')
    <meta name="description" content="Find disc golf tournaments all around the world by map. Results can be filtered and searches can be saved.">
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/instantsearch.js@2.2.3/dist/instantsearch.min.js"></script>
    <script>
        let active = 'search';
    </script>

    <script type="text/html" id="hit-template">
        <a href="@{{ path }}" onClick="ga('send', 'event', 'Search Page', 'View tournament', 'Sidebar list');">
            <div class="uk-grid uk-grid-collapse">
                <div class="uk-width-1-6 uk-text-center">
                    @{{ month }}<br>
                    @{{ day }}
                </div>
                <div class="uk-width-5-6">
                    <h3 class="tournament-name uk-text-truncate uk-margin-remove-bottom">@{{ name }}</h3>
                    <div class="tournament-info">@{{ city }}, @{{ state_province }}, @{{ country }}</div>
                </div>
            </div>
        </a>
    </script>

    <script type="text/html" id="no-results-template">
        <div id="no-results-message" class="uk-padding-small">
            <p>We didn't find any results based on these filter settings.</p>
            <a href="{{ route('search') }}" class="clear-all uk-button uk-button-default uk-button-small">Clear search</a>
        </div>
    </script>
@endpush

@section('content')
    @parent

    <tournament-map :user="user"
                    v-on:show-login="showLogin"
                    v-on:user-updated="updateUser"></tournament-map>
@endsection