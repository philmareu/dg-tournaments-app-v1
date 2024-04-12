@extends('laramanager::layouts.admin.table')

@section('title')
    {{ $title }} ({{ $tournaments->count() }})
@endsection

@section('breadcrumbs')
    <li><span>@yield('title')</span></li>
@endsection

@section('table-headers')
    <th>Id</th>
    <th>Tournament</th>
    <td>Start</td>
    <th>Courses</th>
    <th>Latitude</th>
    <th>Longitude</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
@endsection

@section('table-body')
    @foreach($tournaments as $tournament)
        <tr>
            <td>{{ $tournament->id }}</td>
            <td>{{ $tournament->name }}</td>
            <td data-order="{{ $tournament->start->format('U') }}">{{ $tournament->start->format('m/d/y') }}</td>
            <td>{{ $tournament->courses->count() }}</td>
            <td>{{ $tournament->latitude }}</td>
            <td>{{ $tournament->longitude }}</td>
            <td><a href="{{ url('admin/tournaments/' . $tournament->id) }}" target="_blank">Admin</a></td>
            <td><a href="{{ route('manage.tournament.index', $tournament->id) }}" target="_blank">Manage</a></td>
        </tr>
    @endforeach
@endsection

@section('table-settings')

    <script>
        $(function() {
            $('#data-table').DataTable({
                "pageLength": 250,
                "lengthMenu": [ [100, 250, 500, -1], [100, 250, 500, "All"] ],
                "order": [[2, 'asc']]
            });
        });
    </script>

@endsection