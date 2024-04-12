@extends('layouts.sub.account')

@section('title')
    Profile
@endsection

@section('breadcrumbs')
    <li><span>Account</span></li>
    <li><a href="#">Profile</a></li>
@endsection

@section('side-nav')
    @include('partials.navigation.account', ['active' => 'profile'])
@endsection

@section('page-content')

    <div class="uk-card uk-card-default uk-card-small uk-card-body">
        <form action="{{ route('profile.update') }}" class="uk-form uk-form-stacked" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <img src="{{ url('images/poster-small/' . $user->image->filename) }}" alt="Profile image" class="uk-width-1-6 uk-border-circle">
            @include('laraform::elements.form.image', ['field' => ['name' => 'image', 'class' => '']])

            <div class="uk-margin">
                @include('laraform::elements.form.text', ['field' => ['name' => 'name', 'value' => $user->name, 'class' => 'uk-input uk-form-small']])
            </div>

            <div class="uk-margin">
                @include('laraform::elements.form.text', ['field' => ['name' => 'location', 'value' => $user->location, 'class' => 'uk-input uk-form-small']])
            </div>

            @include('laraform::elements.form.submit', ['class' => 'uk-width-1-1 uk-width-1-4@s uk-text-contrast uk-button-small'])
        </form>
    </div>

@endsection