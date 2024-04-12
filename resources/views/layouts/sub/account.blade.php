@extends('layouts.default')

@section('title')
    Account
@endsection

@section('content')
    @parent

    <div id="layout-side-nav">
        <nav class="uk-navbar uk-navbar-container uk-hidden@s">
            <div class="uk-navbar-left uk-dark">
                <a class="uk-navbar-toggle" uk-navbar-toggle-icon href="#offcanvas-account-nav" uk-toggle></a> Account
            </div>
        </nav>

        <div class="content">

            <div class="container">

                @include('laraform::alerts.default')

                <h1>@yield('title')</h1>

                @yield('page-content')
            </div>

            <div class="uk-hidden@s">
                @include('partials.footer', ['size' => 'small'])
            </div>
        </div>

        <div class="sidebar uk-visible@s">
            <div class="uk-padding-small">
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
        let active = 'account';
    </script>
@endpush