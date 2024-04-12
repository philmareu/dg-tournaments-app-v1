@extends('layouts.sub.page')

@section('title')
    Contact Us
@endsection

@section('meta')
    <meta name="description" content="Contact the staff at DG Tournaments (dgtournaments.com)">
@endsection

@section('page-content')

    @include('laraform::alerts.default')

    <form action="{{ url('contact-us') }}" class="uk-form uk-form-stacked" method="POST" id="submit-feedback">
        {{ csrf_field() }}

        @if(auth()->check())
            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
        @else
            <div class="uk-margin">
                @include('laraform::elements.form.email', ['field' => ['name' => 'email', 'class' => 'uk-input']])
            </div>
        @endif

        <div class="uk-margin">
            @include('laraform::elements.form.textarea', ['field' => ['name' => 'feedback', 'label' => 'Message', 'class' => 'uk-textarea']])
        </div>

        <div class="uk-margin">
            @include('laraform::elements.form.submit', ['class' => 'uk-width-1-3 uk-text-contrast uk-button-small', 'value' => 'Send'])
        </div>
    </form>

@endsection