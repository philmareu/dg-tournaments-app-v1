@extends('layouts.sub.manage.tournament')

@section('title')
    Media
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'media'])
@endsection

@section('page-content')
    @parent

    <div class="uk-margin">
        <media></media>
    </div>
@endsection