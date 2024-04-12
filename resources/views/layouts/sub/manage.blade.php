@extends('layouts.default')

@section('title')
    Manage
@endsection

@section('breadcrumbs')
    <li><span>Tournaments</span></li>
    <li><a href="{{ route('manage.index') }}">Manage</a></li>
@endsection

@section('content')
    @parent

    <div id="layout-side-nav">
        <nav class="uk-navbar uk-navbar-container uk-hidden@s">
            <div class="uk-navbar-left uk-dark">
                <a class="uk-navbar-toggle" uk-navbar-toggle-icon href="#offcanvas-account-nav" uk-toggle></a> Manage
            </div>
        </nav>

        <div class="content">

            <div class="container">
                @if(isset($tournament))
                    <h1 class="uk-light">@yield('title') (<a href="{{ $tournament->path }}">View</a>)</h1>
                @else
                    <h1 class="uk-light">@yield('title')</h1>
                @endif

                @yield('page-content')
            </div>

            <div class="uk-hidden@s">
                @include('partials.footer', ['size' => 'small'])
            </div>
        </div>

        <div class="sidebar uk-visible@s">
            <div class="uk-padding-small uk-height-large">
                @yield('side-nav')
            </div>

            @include('partials.footer', ['size' => 'small'])
        </div>

        <div id="offcanvas-account-nav" uk-offcanvas="overlay: true">
            <div class="uk-offcanvas-bar">
                @yield('side-nav')
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let active = 'manage';
    </script>
@endpush