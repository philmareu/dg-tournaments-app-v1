@extends('partials.activities.layouts.default')

@section('image')
    <img src="{{ url('images/poster-small/' . auth()->user()->image->filename) }}" alt="Round Image" class="uk-border-circle uk-margin" width="40">
@overwrite