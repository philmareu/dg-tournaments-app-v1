@extends('partials.activities.layouts.sub.tournament')

@section('summary')
    <a href="{{ $activity->resource->path }}">{{ $activity->resource->name }}</a> create a new sponsorship
@overwrite

@section('details')
    <h3 class="uk-margin-remove-bottom">{{ $activity->data->title }}</h3>
    <div>
        ${{ $activity->data->cost_in_dollars }}
        <p>{{ $activity->data->description }}</p>
    </div>
@overwrite