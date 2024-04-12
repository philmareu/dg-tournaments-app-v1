@extends('layouts.sub.manage.tournament')

@section('title')
    Followers
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'followers'])
@endsection

@section('page-content')
    @parent

    <div class="uk-card uk-card-default uk-card-small uk-card-body">
        <div class="uk-width-1-3">
            @foreach($tournament->followers as $follow)
                <div class="uk-grid-small" uk-grid>
                    <div class="uk-width-1-6">
                        <img src="{{ url('images/poster-small/' . $follow->user->image->filename) }}" alt="" class="uk-border-circle">
                    </div>
                    <div class="uk-width-5-6">
                        <h3>{{ $follow->user->name }}</h3>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection