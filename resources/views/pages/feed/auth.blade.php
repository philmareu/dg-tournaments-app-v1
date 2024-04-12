@extends('layouts.default')

@include('partials.mapbox')

@section('title')
    Home
@endsection

@section('meta')
    <meta name="description" content="DG Tournaments provides information on disc golf tournaments from around the world. Our powerful search tools plot disc golf tournaments on a map with the ability to filter search results.">
@endsection

@section('body-class')
    home
@endsection

@push('scripts')
    <script>
        let active = 'home';
    </script>
@endpush

@section('content')
    <header>
        @parent
    </header>

    <section class="uk-padding uk-container">
        <div class="uk-grid">
            <div class="uk-width-medium uk-visible@s">
                <div class="uk-card uk-card-default uk-card-small uk-margin-medium">
                    <div class="uk-card-media-top">
                        <img src="{{ url('images/small/' . $user->image->filename) }}" alt="">
                    </div>
                    <div class="uk-card-body">
                        <div>
                            {{ $user->name }}<br>
                            {{ $user->location }}
                        </div>
                        <div>
                            <a href="{{ url('account/profile') }}">Edit Profile</a>
                        </div>
                        <hr>
                        <div class="uk-text-large"><a href="{{ url('user/') }}"></a></div>
                        <div class="uk-text-large">{{ $user->followingTournaments->count() }}</div>
                        <div>Following</div>
                    </div>
                </div>
            </div>
            <div class="uk-width-expand uk-margin-medium-bottom">
                <div class="uk-card uk-card-default uk-card-small">
                    <div class="uk-card-header">
                        <h3 class="uk-card-title">Recent Activity</h3>
                    </div>
                    <div class="uk-card-body">
                        {{--@each('pages.feed.activity', $user->feed, 'activity', 'pages.feed.empty')--}}

                        @foreach($feed as $activity)
                            {!! $activity !!}
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="uk-width-large">
                <div class="uk-card uk-card-default uk-card-small">
                    <div class="uk-card-header">
                        <h3 class="uk-card-title">Following</h3>
                    </div>
                    <div class="uk-card-body">
                        @foreach($upcoming as $follow)

                            <div class="uk-grid uk-grid-small uk-flex uk-flex-middle">
                                <div class="uk-width-auto">
                                    <img src="{{ url('images/small/' . $follow->resource->poster->filename) }}" alt="Round Image" class="uk-margin" width="40">
                                </div>
                                <div class="uk-width-expand">
                                    <div><a href="{{ $follow->resource->path }}">{{ $follow->resource->name }}</a></div>
                                    <div class="uk-text-muted">{{ $follow->resource->dateSpan->formattedDateSpan() }}</div>
                                </div>
                            </div>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('footer')
    @include('partials.footer', ['size' => 'large'])
@endsection