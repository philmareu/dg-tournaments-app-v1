@extends('layouts.sub.manage')

@section('title')
    Submit Tournament
@endsection

@include('partials.mapbox')

@section('side-nav')
    @include('partials.navigation.manage.tools', ['active' => 'submit'])
@endsection

@section('breadcrumbs')
    <li><span>Tournaments</span></li>
    <li><a href="{{ route('manage.index') }}">Manage</a></li>
@endsection

@section('page-content')
    @parent

    <div class="uk-card uk-card-default uk-card-small uk-card-body">
        <submit-tournament></submit-tournament>
    </div>
@endsection

@push('scripts')
    <script>
        let specialEventTypes = {!! $specialEventTypes->toJson() !!};
        let timezones = {!! json_encode($timezones) !!};
        let formats = {!! $formats->toJson() !!};
        let classes = {!! $classes->toJson() !!};
    </script>
@endpush
