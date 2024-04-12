@extends('layouts.sub.manage.tournament')

@section('title')
    Registration
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'registration'])
@endsection

@section('page-content')
    @parent

    <div class="uk-margin">
        <registration></registration>
    </div>
@endsection