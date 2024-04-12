@extends('layouts.sub.manage')

@section('title')
    Sponsor Library
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tools', ['active' => 'sponsor-library'])
@endsection

@section('breadcrumbs')
    <li><span>Tournaments</span></li>
    <li><span>Manage</span></li>
    <li><span><a href="#">Sponsor Library</a></span></li>
@endsection

@section('page-content')
    <sponsor-library></sponsor-library>
@endsection