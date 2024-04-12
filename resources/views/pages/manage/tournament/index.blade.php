@extends('layouts.sub.manage.tournament')

@section('title')
    Tournament Dashboard
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'dashboard'])
@endsection

@section('breadcrumbs')
    <li><span>Tournaments</span></li>
    <li><a href="{{ route('manage.index') }}">Manage</a></li>
@endsection

@section('page-content')
    
@endsection