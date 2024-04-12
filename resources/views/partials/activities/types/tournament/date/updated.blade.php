@extends('partials.activities.layouts.sub.tournament')

@section('summary')
    <a href="{{ $activity->resource->path }}">{{ $activity->resource->name }}</a> updated tournament date(s)
@overwrite

@section('details')
    The date was changed to {{ $activity->resource->dateSpan->formattedDateSpan() }}.
@overwrite