@extends('layouts.default')

@section('content')
    @parent

    <ul class="page-header uk-light uk-breadcrumb">
        @yield('breadcrumbs')
    </ul>

    @include('laraform::alerts.default')

    <div class="container">
        <div uk-grid>
            <div class="uk-width-2-3@s">
                <h1>@yield('title')</h1>
                @yield('page-content')
            </div>
            <div class="uk-width-1-3@s">
                @include('partials.sidebar.newsletter')
            </div>
        </div>
    </div>
@endsection

@section('footer')
    @include('partials.footer', ['size' => 'large'])
@endsection