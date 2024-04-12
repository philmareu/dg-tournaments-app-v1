@extends('layouts.sub.account')

@section('title')
    Notifications
@endsection

@section('breadcrumbs')
    <li><span>Account</span></li>
    <li><a href="#">Email Notifications</a></li>
@endsection

@section('side-nav')
    @include('partials.navigation.account', ['active' => 'notifications'])
@endsection

@section('page-content')

    <div class="uk-card uk-card-default uk-card-small">
        <div class="uk-card-header">
            <h3 class="uk-card-title">Email Notifications</h3>
        </div>

        <div class="uk-card-body">
            <form action="{{ url('account/notifications') }}" class="uk-form uk-form-stacked" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}

                @foreach($emailNotificationTypes as $type)
                    <div class="uk-margin">
                        <label><input type="checkbox" name="email_notifications[]" value="{{ $type->id }}" class="uk-checkbox" {{ $user->emailNotificationSettings->where('id', $type->id)->isEmpty() ? '' : 'checked="checked"' }}> {{ $type->label }}</label>
                    </div>
                @endforeach

                @include('laraform::elements.form.submit', ['class' => 'uk-width-1-1 uk-width-1-4@s uk-text-contrast uk-button-small'])
            </form>
        </div>
    </div>

@endsection