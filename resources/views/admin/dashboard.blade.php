@extends('laramanager::layouts.admin.table')

@section('title')
    Dashboard
@endsection

@section('breadcrumbs')
    <li><span>@yield('title')</span></li>
@endsection

@section('table-headers')
    <th>Resource</th>
    <th>Count</th>
@endsection

@section('table-body')
    @foreach($counts as $count)
        <tr>
            <td>{{ $count['title'] }}</td>
            <td>{{ $count['quantity'] }}</td>
        </tr>
    @endforeach
@endsection