@extends('layouts.sub.account')

@section('title')
    Memberships
@endsection

@section('breadcrumbs')
    <li><span>Account</span></li>
    <li><a href="#">Memberships</a></li>
@endsection

@section('side-nav')
    @include('partials.navigation.account', ['active' => 'memberships'])
@endsection

@section('page-content')

    <div class="uk-card uk-card-default uk-card-small">
        <div class="uk-card-header">
            <h3 class="uk-card-title">PDGA Information</h3>
        </div>
        <div class="uk-card-body">
            <p>Not a member? <a href="https://www.pdga.com/membership" target="_blank">Become a member.</a></p>

            <form action="{{ route('account.memberships.update') }}" class="uk-form uk-form-stacked" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                <p class="uk-margin"><span class="uk-text-bold">Current Rating:</span> {{ is_null($user->pdga_rating) ? 'N/A' : $user->pdga_rating }}</p>

                <div class="uk-margin">
                    @include('laraform::elements.form.text', ['field' => ['name' => 'pdga_number', 'label' => 'PDGA Number', 'value' => $user->pdga_number, 'class' => 'uk-input uk-form-small']])
                </div>

                @include('laraform::elements.form.submit', ['class' => 'uk-width-1-1 uk-width-1-4@s uk-text-contrast uk-button-small'])
            </form>
        </div>
    </div>

@endsection