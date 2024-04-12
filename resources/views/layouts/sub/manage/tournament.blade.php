@extends('layouts.default')

@include('partials.mapbox')

@section('content')

    <div class="title-bar uk-flex uk-flex-center uk-flex-middle uk-text-center">
        <h1 class="uk-light uk-margin-remove">{{ $tournament->name }}</h1>
    </div>

    <div class="uk-grid uk-grid-collapse">
        <div class="uk-width-1-5@s uk-visible@s">
            <div class="tournament-nav uk-light">
                @yield('side-nav')
            </div>
        </div>
        <div class="uk-width-4-5@s">
            <nav class="uk-navbar uk-navbar-container uk-hidden@s">
                <div class="uk-navbar-left uk-dark">
                    <a class="uk-navbar-toggle" uk-navbar-toggle-icon href="#offcanvas-account-nav" uk-toggle></a> Manage Tournament
                </div>
            </nav>
            <div class="tournament-content uk-padding">
                <h1>@yield('title')</h1>
                @yield('page-content')
            </div>

            <div class="uk-hidden@s">
                @include('partials.footer', ['size' => 'small'])
            </div>

        </div>
    </div>

    <div class="uk-visible@s">
        @include('partials.footer', ['size' => 'large'])
    </div>

    {{--<div id="layout-side-nav">--}}

        {{--<div class="content">--}}
            {{--<div class="container">--}}
                {{--<h1 class="uk-light">@yield('title')</h1>--}}

                {{--@yield('page-content')--}}
            {{--</div>--}}


        {{--</div>--}}

        {{--<div class="sidebar uk-visible@s">--}}
            {{--<div class="uk-padding-small">--}}
                {{--@yield('side-nav')--}}
            {{--</div>--}}
        {{--</div>--}}

    <div id="offcanvas-account-nav" uk-offcanvas="overlay: true">
        <div class="uk-offcanvas-bar">
            @yield('side-nav')
        </div>
    </div>

    {{--</div>--}}
@endsection

@push('scripts')
    <script>
        let tournament = {!! $tournament->load('courses', 'sponsorships')->toJson() !!};
    </script>
@endpush