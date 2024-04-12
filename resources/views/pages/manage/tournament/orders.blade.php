@extends('layouts.sub.manage.tournament')

@section('title')
    Orders
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'orders'])
@endsection

@section('page-content')
    @parent

    <div class="uk-card uk-card-default uk-card-small uk-overflow-auto uk-card-body">
        <table class="uk-table uk-table-small uk-table-striped">
            <thead>
            <tr>
                <td>ID</td>
                <td>Date</td>
                <td>Name</td>
                <td>Email</td>
                <td>Total</td>
            </tr>
            </thead>
            <tbody>
            @foreach($tournament->transfers as $transfer)
                <tr>
                    <td><a href="{{ url('manage/' . $tournament->id . '/order/' . $transfer->id) }}">{{ $transfer->id }}</a></td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transfer->created_at)->format('M d, Y') }}</td>
                    <td>{{ $transfer->order->first_name }} {{ $transfer->order->last_name }}</td>
                    <td>{{ $transfer->order->email }}</td>
                    <td>${{ $transfer->total->formatted() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection