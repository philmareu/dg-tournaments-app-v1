@extends('layouts.sub.auth')

@section('title')
    Login
@endsection

@section('page-content')

    <div class="uk-container uk-padding uk-text-center uk-width-1-1 uk-width-1-3@s">
        <a href="{{ url('/') }}">
            <span uk-icon="icon: dgt; ratio: .4"></span>
        </a>

        <div class="title uk-margin">DG Tournaments</div>

        @if(session()->has('success'))
            <div class="uk-alert uk-alert-success" data-uk-alert>
                <a href="#" class="uk-alert-close uk-close"></a>
                {{ session('success') }}
            </div>
        @elseif(session()->has('failed'))
            <div class="uk-alert uk-alert-danger" data-uk-alert>
                <a href="#" class="uk-alert-close uk-close"></a>
                {{ session('failed') }}
            </div>
        @endif

        <div class="uk-card uk-card-default uk-card-small">

            <div class="uk-card-header uk-text-left">
                <h3 class="uk-card-title">Login</h3>
            </div>

            <div class="uk-card-body">

                <div class="uk-grid uk-grid-small">
                    <div class="uk-width-1-2@s">
                        <a href="{{ route('auth.facebook') }}" class="uk-button uk-button-default uk-width-1-1"><span uk-icon="icon: facebook;" class="uk-margin-small-right"></span>Facebook</a>
                        <hr class="uk-hidden@s">
                    </div>
                    <div class="uk-width-1-2@s">
                        <a href="{{ route('auth.twitter') }}" class="uk-button uk-button-default uk-width-1-1"><span uk-icon="icon: twitter;" class="uk-margin-small-right"></span>Twitter</a>
                    </div>
                </div>

                <div class="uk-text-center uk-text-small uk-margin">or</div>

                <form class="uk-form uk-form-stacked uk-text-left" role="form" method="POST" action="{{ url('/login') }}">
                    {{ csrf_field() }}

                    <div class="uk-text-danger">
                        {{ $errors->first('email') }}
                    </div>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: mail"></span>
                        <input class="uk-input" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
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

                    <div class="uk-margin uk-dark">
                        <label for="remember" class="uk-form-label">
                            <input id="remember" type="checkbox" value="1" name="remember" class="uk-checkbox"> Keep me signed in
                        </label>
                    </div>

                    <button class="uk-button uk-button-primary uk-width-1-1" type="submit">Sign In</button>

                    <p class="uk-text-center"><a href="{{ url('/password/reset') }}" class="">Forgot Password</a></p>

                    <hr>

                    <div class="uk-text-center">
                        <a class="uk-button uk-button-default uk-text-center" href="{{ route('register') }}">I need an account.</a>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
