@extends('partials.activities.layouts.sub.tournament')

@section('summary')
    <a href="{{ $activity->resource->path }}">{{ $activity->resource->name }}</a> registration will open {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activity->data->opens_at)->diffForHumans() }}
@overwrite

@section('details')
    @if($activity->data->url != '' && \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activity->data->opens_at)->isPast())
        <div>
            <a href="{{ $activity->data->url }}" class="uk-button uk-button-small uk-button-default">Register now</a>
        </div>
    @endif
@overwrite