@extends('layouts.sub.manage')

@section('title')
    Stripe Accounts
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tools', ['active' => 'stripe-accounts'])
@endsection

@section('breadcrumbs')
    <li><span>Tournaments</span></li>
    <li><a href="{{ route('manage.index') }}">Manage</a></li>
@endsection

@section('page-content')
    @parent

    <div class="uk-card uk-card-default uk-card-small uk-card-body uk-margin">
        <p><a href="https://stripe.com" target="_blank">Stripe</a> is a great solution for handling online payments for business. If you have Stripe accounts you can connect them here for collecting credit card payments for tournaments. If you don't have a Stripe account it is super easy to setup and connect to your bank account. Check out
            <a href="https://stripe.com" target="_blank">https://stripe.com</a> for more information.</p>
        <a href="{{ $url }}" class="uk-button uk-button-primary uk-button-small">Connect New Account</a>
    </div>

    <stripe-accounts :accounts="{{ auth()->user()->stripeAccounts }}"></stripe-accounts>
@endsection
