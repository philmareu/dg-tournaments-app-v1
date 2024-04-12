@extends('partials.activities.layouts.sub.tournament')

@section('summary')
    <a href="{{ $activity->resource->path }}">{{ $activity->resource->name }}</a> updated description
@overwrite

@section('details')

@overwrite