@extends('partials.activities.layouts.sub.user')

@section('summary')
    New tournaments listed
@overwrite

@section('details')
    <ul class="uk-list">
        @foreach($activity->data as $tournament)
            <li><a href="{{ url($tournament->path) }}">{{ $tournament->name }}</a>, {{ $tournament->location }} - {{ $tournament->date_span }}</li>
        @endforeach
    </ul>
@overwrite