@extends('layouts.sub.manage')

@section('title')
    Dashboard
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tools', ['active' => 'dashboard'])
@endsection

@section('breadcrumbs')
    <li><span>Tournaments</span></li>
    <li><a href="{{ route('manage.index') }}">Manage</a></li>
@endsection

@section('page-content')
    @parent

    @each('pages.manage.tools.tournaments.list', $tournaments, 'tournament', 'pages.manage.tools.tournaments.empty')

@endsection