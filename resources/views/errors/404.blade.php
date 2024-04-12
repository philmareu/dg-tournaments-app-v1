@extends('layouts.default')

@section('title')
    404
@endsection

@section('content')
    @parent

    <section class="uk-section uk-text-center">
        <p class="uk-text-large">Sorry, this page doesn't exist.</p>
    </section>

@endsection