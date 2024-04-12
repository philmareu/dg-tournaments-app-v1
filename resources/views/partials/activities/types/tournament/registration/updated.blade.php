@extends('partials.activities.layouts.sub.tournament')

@section('summary')
    <a href="{{ $activity->resource->path }}">{{ $activity->resource->name }}</a> updated registration
@overwrite

@section('details')

    @php
        $open = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activity->data->opens_at);
    @endphp

    <div class="uk-margin">
        @if(is_null($activity->data->closes_at))
            @if($open->isFuture())
                It will open {{ $open->format('M d, Y') }}.
            @endif
        @else
            @php
                $close = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $activity->data->closes_at);
            @endphp

            @if($open->isFuture())
                It will open {{ $open->format('M d, Y') }}
                and close {{ $close->format('M d, Y') }}.
            @else
                Registration is open and will close {{ $close->format('M d, Y') }}.
            @endif
        @endif
    </div>

    @if($activity->data->url != '' && $open->isPast())
        <div>
            <a href="{{ $activity->data->url }}" class="uk-button uk-button-small uk-button-default">Register now</a>
        </div>
    @endif
@overwrite