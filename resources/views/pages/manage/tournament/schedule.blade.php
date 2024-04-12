@extends('layouts.sub.manage.tournament')

@section('title')
    Schedule
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'schedule'])
@endsection

@section('page-content')
    @parent

    <div class="uk-margin">
        <schedule></schedule>
    </div>
@endsection