@extends('layouts.sub.manage.tournament')

@section('title')
    Player Packs
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'player-packs'])
@endsection

@section('page-content')
    @parent

    <div class="uk-margin">
        <player-packs></player-packs>
    </div>
@endsection