@extends('layouts.sub.page')

@section('title')
    Tournament Claim Request
@endsection

@push('scripts')
    <script>
        var claim = {!! $claim->toJson() !!};
    </script>
@endpush

@section('page-content')
    @parent

    <p>Approve {{ $claim->user->name }} to manage the disc golf tournament page for <a href="{{ $claim->tournament->path }}">{{ $claim->tournament->name }}</a>.</p>

    <approve-claim-request></approve-claim-request>

@endsection