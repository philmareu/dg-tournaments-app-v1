@extends('laramanager::layouts.default')

@section('head')
    <link href="{{ asset("vendor/laramanager/css/datatables.css") }}" rel="stylesheet" media="screen">
@endsection

@section('title')
    Events with No Courses
@endsection

@section('content')
    <div class="uk-overflow-container">
        <table id="data-table" class="stripe row-border">
            <thead>
            <tr>
                <td>Name</td>
                <td>Date</td>
                <td>City</td>
                <td>State</td>
                <td>Country</td>
                <td>Featured</td>
                <td>&nbsp;</td>
            </tr>
            </thead>

            <tbody>
            @foreach($events as $event)
                <tr>
                    <td>{{ $event->tournament_name }}</td>
                    <td>{{ $event->start_date->format('M d, Y') }}</td>
                    <td>{{ $event->city }}</td>
                    <td>{{ $event->state_prov }}</td>
                    <td>{{ $event->country }}</td>
                    <td>{!! $event->featured ? '<i class="uk-icon-check"></i>' : '' !!}</td>
                    <td>
                        <a href="{{ route('admin.events.edit', $event->tournament_id) }}"><i class="uk-icon-pencil"></i></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection

@section('scripts')

    <script src="{{ asset('vendor/laramanager/js/datatables.js') }}"></script>

    <script>
        $(function() {
            $('#data-table').DataTable({
                "pageLength": 50,
                "order": [[1, 'asc']]
            });
        });
    </script>

@endsection