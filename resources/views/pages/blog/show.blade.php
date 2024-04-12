@extends('layouts.sub.page')

@section('title')
    {{ $post->title }}
@endsection

@section('breadcrumbs')
    <li><a href="{{ url('blog') }}">Blog</a></li>
    <li><a href="{{ url($post->path) }}">{{ $post->title }}</a></li>
@endsection

@section('page-content')
    <div class="uk-card uk-card-small uk-card-body bg-light">
        {!! $post->body !!}
    </div>
@endsection