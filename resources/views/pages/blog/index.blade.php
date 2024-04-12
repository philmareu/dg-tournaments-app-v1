@extends('layouts.sub.page')

@section('title')
    Blog
    {{ $posts->currentPage() > 1 ? '(page ' . $posts->currentPage() . ')' : '' }}
@endsection

@section('page-content')
    @each('pages.blog.summary', $posts, 'post')
    {{ $posts->links() }}
@endsection