@extends('layouts.sub.auth')

@section('title')
    Register
@endsection

@section('page-content')
    <div class="uk-container uk-padding uk-text-center uk-width-1-1 uk-width-1-3@s">
        <a href="{{ url('/') }}">
            <span uk-icon="icon: dgt; ratio: .4"></span>
        </a>

        <div class="title uk-margin">DG Tournaments</div>

        <div class="uk-card uk-card-default uk-card-small">

            <div class="uk-card-header uk-text-left">
                <h3 class="uk-card-title">Register</h3>
            </div>

            <div class="uk-card-body">

                @if(isset($referral))
                    <p class="uk-text-bold uk-text-warning uk-text-large">{{ $referral->referredBy->name }} invited you to DG Tournaments</p>
                @endif

                <form class="uk-form uk-form-stacked uk-text-left" role="form" method="POST" action="{{ url('/register') }}">
                    {{ csrf_field() }}

                    @if(isset($referral))
                        <input type="hidden" name="referral" value="{{ $referral->code }}">
                    @endif

                    <div class="uk-text-danger">
                        {{ $errors->first('name') }}
                    </div>
                    <div class="uk-inline uk-width-1-1">
                        <span class="uk-form-icon" uk-icon="icon: user"></span>
                        <input class="uk-input " type="text" name="name" placeholder="Name" value="{{ old('name') }}">
                    </div>

                    <div class="uk-margin">
                        <div class="uk-text-danger">
                            {{ $errors->first('email') }}
                        </div>
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon" uk-icon="icon: mail"></span>
                            <input class="uk-input " type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                        </div>
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
                        <div class="uk-text-danger">
                            {{ $errors->first('password_confirmation') }}
                        </div>
                        <div class="uk-inline uk-width-1-1">
                            <span class="uk-form-icon" uk-icon="icon: lock"></span>
                            <input class="uk-input" type="password" name="password_confirmation" placeholder="Confirm Password">
                        </div>
                    </div>

                    <div class="uk-margin btn-login">
                        @include('laraform::elements.form.submit', ['value' => 'Create Account', 'class' => 'uk-width-1-1 uk-button uk-text-contrast'])
                    </div>
                    <p class="uk-dark">By signing up, you agree to the <a href="{{ url('terms-of-service') }}">Terms of Service</a> and <a
                                href="{{ url('privacy-policy') }}">Privacy Policy</a>.</p>
                </form>
            </div>
        </div>
    </div>
@endsection
