@extends('partials.activities.layouts.sub.user')

@section('summary')
    You now have permission to manage <a href="{{ url($activity->resource->path) }}">{{ $activity->resource->name }}</a>.
@overwrite

@section('details')

@overwrite