@extends('layouts.sub.auth')

@section('title')
    Reset Password
@endsection

@section('css-after')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('page-content')

    <div class="uk-container uk-padding uk-text-center uk-width-1-1 uk-width-1-3@s">
        <a href="{{ url('/') }}">
            <span uk-icon="icon: dgt; ratio: .8"></span>
        </a>

        <h1 class="uk-dark uk-text-small">DG Tournaments</h1>

        @include('partials.alerts.default')

        <div class="uk-card uk-card-default uk-card-small">

            <div class="uk-card-header uk-text-left">
                <h3 class="uk-card-title">Reset your password.</h3>
            </div>

            <div class="uk-card-body">
                <form class="uk-form uk-form-stacked uk-text-left" role="form" method="POST" action="{{ url('/password/reset') }}">
                    {{ csrf_field() }}

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="uk-text-danger">
                        {{ $errors->first('email') }}
                    </div>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                        <input class="uk-input " type="email" name="email" placeholder="Email">
                    </div>

                    <div class="uk-margin">
                        <div class="uk-text-danger">
                            {{ $errors->first('password') }}
                        </div>
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon" uk-icon="icon: lock"></span>
                            <input class="uk-input" type="password" name="password" placeholder="Password">
                        </div>
                    </div>

                    <div class="uk-margin">
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon" uk-icon="icon: lock"></span>
                            <input class="uk-input" type="password" name="password_confirmation" placeholder="Confirm Password">
                        </div>
                    </div>

                    <div class="uk-margin btn-login">
                        @include('laraform::elements.form.submit', ['value' => 'Reset', 'class' => 'uk-width-1-1 uk-button uk-text-contrast'])
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
