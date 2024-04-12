@extends('partials.activities.layouts.sub.tournament')

@section('summary')
    <a href="{{ $activity->resource->path }}">{{ $activity->resource->name }}</a> added a course
@overwrite

@section('details')
    <h3 class="uk-margin-remove-bottom">{{ $activity->data->name }}</h3>
    <div>
        {{ $activity->data->address }}<br>
        {{ $activity->data->city }}, {{ $activity->data->state_province }}
    </div>
@overwrite