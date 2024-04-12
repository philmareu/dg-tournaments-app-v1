@extends('layouts.sub.manage.tournament')

@section('title')
    Links
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'links'])
@endsection

@section('page-content')
    @parent

    <div class="uk-margin">
        <links></links>
    </div>
@endsection