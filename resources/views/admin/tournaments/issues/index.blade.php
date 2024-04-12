@extends('laramanager::layouts.default')

@section('title')
    Tournament Issues
@endsection

@section('content')

    <table class="uk-table uk-table-condensed uk-table-striped">
        <thead>
            <tr>
                <th>Tournament</th>
                <th>Issue</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            @foreach($issues as $issue)
                <tr>
                    <td>{{ $issue->tournament->tournament_name }}</td>
                    <td>{{ $issue->issue }}</td>
                    <td>{{ $issue->created_at->format('m/d/Y') }}</td>
                    <td>{{ $issue->updated_at->format('m/d/Y') }}</td>
                    <td><a href="{{ route('tournaments.edit', $issue->tournament->tournament_id) }}">View</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection