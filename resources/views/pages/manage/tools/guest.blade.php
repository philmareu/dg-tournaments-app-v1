@extends('layouts.default')

@section('title')
    Manage Tournaments
@endsection

@section('content')
    @parent

    <div class="uk-container uk-container-small uk-flex uk-flex-middle uk-flex-center uk-margin-top">
        <div class="uk-card uk-card-default uk-card-small">
            <div class="uk-padding uk-text-center uk-text-muted">
                <span uk-icon="icon: location; ratio: 4;"></span>
                <p>DG Tournaments management tools make it easy to keep your players informed, sell sponsorships and more. It's simple and free. Go ahead and <a
                            href="{{ route('login') }}">login</a> or <a href="{{ route('register') }}">create an account</a> and try it out.</p>
                <p>If you have a PDGA sanctioned event, it should already be listed. You should <a href="{{ route('search') }}">search</a> for your event first and then select the "claim" event button on the event page.</p>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        let active = 'manage';
    </script>
@endpush