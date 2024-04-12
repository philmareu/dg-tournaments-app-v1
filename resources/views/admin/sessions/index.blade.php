@extends('laramanager::layouts.admin.blank')

@section('title')
    Sessions
@endsection

@section('breadcrumbs')
    <li><span>@yield('title')</span></li>
@endsection

@section('blank-content')

    @foreach($sessions as $requests)
        <div class="uk-card uk-card-default uk-card-small uk-margin">
            <div class="uk-card-header">
                <h3 class="uk-card-title">{{ $requests->first()->ip }}</h3>
            </div>
            <div class="uk-card-body">
                <table class="uk-table uk-table-striped uk-table-small" id="data-table">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Method</th>
                        <th>URI</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($requests as $request)
                        <tr>
                            <td>{{ $request->created_at->timezone('America/Los_Angeles')->format('M dS') }}</td>
                            <td>{{ $request->created_at->timezone('America/Los_Angeles')->format('h:iA') }}</td>
                            <td>{{ $request->method }}</td>
                            <td>{{ $request->uri }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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