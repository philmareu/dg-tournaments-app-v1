@extends('layouts.default')

@section('content')
    @parent

    <div class="container">

        <div class="uk-hidden@s uk-card uk-card-default uk-card-small uk-card-body uk-margin-small-bottom">
            <ul uk-accordion>
                <li>
                    <div class="uk-accordion-title uk-text-small">@yield('title')</div>
                    <div class="uk-accordion-content">
                        <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
                            @yield('side-nav')
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <div class="uk-grid">
            <div class="uk-width-1-4@s uk-visible@s">
                <div class="uk-card uk-card-default uk-card-small uk-margin-bottom">
                    @yield('side-nav-header')

                    <div class="uk-card-body">
                        <ul class="uk-nav-default uk-nav-parent-icon" uk-nav>
                            @yield('side-nav')
                        </ul>
                    </div>
                </div>
            </div>

            <div class="uk-width-1-1 uk-width-3-4@s">

                @include('laraform::alerts.default')

                @yield('page-content')

                @yield('below-page-content')

            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('partials.footer', ['size' => 'large'])
@endsection