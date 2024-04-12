@extends('admin.layouts.default')

@section('meta')
    <link href="{{ asset("css/datatables/style/datatables.min.css") }}" rel="stylesheet" media="screen">
@endsection

@section('title')
    {{ $director->name }}
@endsection

@section('content')

    <h1>@yield('title')</h1>

    <table class="uk-table" id="data-table">
        <thead>
        <tr>
            <th>Name</th>
            <th>Date</th>
            <td>Active</td>
            <th>Needs Course Info</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($director->directingTournaments as $tournament)
            <tr>
                <td>{{ $tournament->tournament_name }}</td>
                <td data-order="{{ $tournament->start_date->format('U') }}">{{ $tournament->start_date->format('M d, Y') }}</td>
                <td>{{ $tournament->active }}</td>
                <td>{{ $tournament->needs_course_info }}</td>
                <td><a href="{{ route('tournaments.edit', $tournament->tournament_id) }}">Edit</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

@section('scripts')

    <script src="{{ asset('js/datatables.js') }}"></script>

    <script>
        $(function() {
            $('#data-table').DataTable({
                "pageLength": 250,
                "lengthMenu": [ [100, 250, 500, -1], [100, 250, 500, "All"] ],
                "order": [[1, 'desc'], [0, 'asc']]
            });
        });
    </script>

@endsection