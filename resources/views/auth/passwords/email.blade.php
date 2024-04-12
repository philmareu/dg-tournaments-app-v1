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

        <div class="title uk-margin">DG Tournaments</div>

        @include('partials.alerts.default')

        <div class="uk-card uk-card-default uk-card-small">

            <div class="uk-card-header uk-text-left">
                <h3 class="uk-card-title">Reset your password.</h3>
            </div>

            <div class="uk-card-body">

                <form class="uk-form uk-form-stacked uk-text-left" role="form" method="POST" action="{{ url('/password/email') }}">
                    {{ csrf_field() }}

                    <div class="uk-text-danger">
                        {{ $errors->first('email') }}
                    </div>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                        <input class="uk-input " type="email" name="email" placeholder="Email">
                    </div>

                    <div class="uk-margin btn-login">
                        <div class="uk-flex uk-flex-middle uk-text-center" uk-grid>
                            <div class="uk-width-1-2">
                                @include('laraform::elements.form.submit', ['value' => 'Submit', 'class' => 'uk-width-1-1 uk-button uk-text-contrast'])
                            </div>
                            <div class="uk-width-1-2">
                                <a href="{{ url('/login') }}" class="uk-button uk-button-default uk-width-1-1 uk-dark">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
