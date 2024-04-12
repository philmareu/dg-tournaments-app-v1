@extends('partials.activities.layouts.sub.user')

@section('summary')
    PDGA Rating updated
@overwrite

@section('details')
    Your PDGA rating changed from {{ $activity->data->old }} to {{ $activity->data->new }}.
@overwrite