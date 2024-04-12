@extends('layouts.sub.manage.tournament')

@section('title')
    {{ $course->name }}
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'course-information'])
@endsection

@section('page-content')
    @parent

    <course :course="{{ $course->toJson() }}"></course>
@endsection