@extends('partials.activities.layouts.sub.tournament')

@section('summary')
    <a href="{{ $activity->resource->path }}">{{ $activity->resource->name }}</a> updated name
@overwrite

@section('details')
    <div>
        The name was changed from "{{ $activity->data->old }}" to "{{ isset($activity->data->new) ? $activity->data->new : $activity->resource->name }}".
    </div>
@overwrite