@extends('layouts.sub.manage.tournament')

@section('title')
    Basics
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'basics'])
@endsection

@section('page-content')
    @parent

    <div class="uk-margin">
        <information></information>
    </div>

@endsection

@push('scripts')
    <script>
        let timezones = {!! json_encode($timezones) !!};
        let classes = {!! $classes->toJson() !!};
        let formats = {!! $formats->toJson() !!};
    </script>
@endpush