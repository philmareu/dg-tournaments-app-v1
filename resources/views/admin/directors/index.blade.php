@extends('admin.layouts.default')

@section('meta')
    <link href="{{ asset("css/datatables/style/datatables.min.css") }}" rel="stylesheet" media="screen">
@endsection

@section('title')
    Directors
@endsection

@section('content')

    <h1>@yield('title')</h1>

    <table class="uk-table" id="data-table">
        <thead>
        <tr>
            <th>Name</th>
            <td>Inactive Tournaments</td>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody>
        @foreach($directors as $director)
            <tr>
                <td>{{ $director->name }}</td>
                <td>{{ $director->inactiveTournaments->count() }}</td>
                <td><a href="{{ route('directors.show', $director->pdga_number) }}">View</a></td>
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
                "order": [[1, 'asc']]
            });
        });
    </script>

@endsection