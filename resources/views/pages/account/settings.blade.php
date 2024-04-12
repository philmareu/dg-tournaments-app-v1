@extends('layouts.sub.account')

@section('title')
    Settings
@endsection

@section('breadcrumbs')
    <li><span>Account</span></li>
    <li><a href="#">Settings</a></li>
@endsection

@section('side-nav')
    @include('partials.navigation.account', ['active' => 'password'])
@endsection

@section('page-content')

    <div class="uk-card uk-card-default uk-card-small uk-card-body">
        <form action="{{ route('account.settings.update') }}" class="uk-form uk-form-stacked" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="uk-margin">
                @include('laraform::elements.form.email', ['field' => ['name' => 'email', 'value' => auth()->user()->email, 'class' => 'uk-input uk-form-small']])
            </div>

            <div class="uk-margin-bottom">
                @include('laraform::elements.form.password', ['field' => ['name' => 'password', 'class' => 'uk-input uk-form-small', 'label' => 'New Password']])
            </div>

            @include('laraform::elements.form.submit', ['class' => 'uk-width-1-1 uk-width-1-4@s uk-text-contrast uk-button-small'])
        </form>
    </div>

@endsection