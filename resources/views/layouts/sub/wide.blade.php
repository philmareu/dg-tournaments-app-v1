@extends('layouts.default')

@section('content')
    @parent

    @yield('sub-nav')

    <div class="page-container">

        <ul class="uk-breadcrumb">
            <li><a href="{{ url('/') }}">Home</a></li>
            @yield('breadcrumbs')
        </ul>

        <h1>@yield('title')</h1>
        @yield('page-content')
    </div>
@endsection

@section('js-components')

@endsection