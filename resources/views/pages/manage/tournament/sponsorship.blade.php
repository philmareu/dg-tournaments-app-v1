@extends('layouts.sub.manage.tournament')

@section('title')
    {{ $sponsorship->title }}
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'sponsorships'])
@endsection

@section('page-content')
    @parent

    <sponsorship :sponsorship="{{ $sponsorship->toJson() }}"></sponsorship>
@endsection