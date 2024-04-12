@extends('layouts.sub.account')

@section('title')
    Referral
@endsection

@section('side-nav')
    @include('partials.navigation.account', ['active' => 'referral'])
@endsection

@section('page-content')

    <div class="uk-card uk-card-default uk-card-small uk-card-body">
        <p>Refer a friend.</p>

        @include('laraform::alerts.default')
        <form action="{{ route('referral.store') }}" class="uk-form uk-form-stacked" method="POST">
            {{ csrf_field() }}

            <div class="uk-margin">
                @include('laraform::elements.form.email', ['field' => ['name' => 'email', 'class' => 'uk-input uk-form-small']])
            </div>

            <div class="uk-form-row">
                @include('laraform::elements.form.submit', ['class' => 'uk-width-medium-1-2 uk-text-contrast uk-button-small uk-width-1-4@s', 'value' => 'Send'])
            </div>
        </form>
    </div>

@endsection