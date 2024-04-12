@extends('layouts.sub.page')

@section('title')
    {{ $page->title }}
@endsection

@section('breadcrumbs')
    <li><a href="{{ $page->slug }}">{{ $page->title }}</a></li>
@endsection

@section('meta')
    <meta name="description" content="{{ $page->description }}">
@endsection

@section('page-content')

    {!! $page->body !!}

@endsection