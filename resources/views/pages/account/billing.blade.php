@extends('layouts.sub.account')

@section('title')
    Billing
@endsection

@section('breadcrumbs')
    <li><span>Account</span></li>
    <li><a href="#">Billing</a></li>
@endsection

@section('side-nav')
    @include('partials.navigation.account', ['active' => 'billing'])
@endsection

@section('page-content')

    <div class="uk-card uk-card-default uk-card-small uk-card-body">
        <credit-cards :user="user"></credit-cards>
    </div>
@endsection