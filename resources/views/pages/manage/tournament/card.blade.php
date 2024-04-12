@extends('layouts.sub.manage.tournament')

@section('title')
    Credit Card Settings
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'credit-card'])
@endsection

@section('page-content')
    @parent

    <stripe :user="user"></stripe>
@endsection