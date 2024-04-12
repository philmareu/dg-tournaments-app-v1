@extends('layouts.sub.account')

@section('title')
    Orders
@endsection

@section('breadcrumbs')
    <li><span>Account</span></li>
    <li><a href="#">Orders</a></li>
@endsection

@section('side-nav')
    @include('partials.navigation.account', ['active' => 'orders'])
@endsection

@section('page-content')

    <div class="uk-card uk-card-default uk-card-small uk-card-body">
        <div class="uk-card-body">
            <div class="uk-overflow-auto">
                <table class="uk-table uk-table-condensed uk-table-small uk-table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Date</th>
                        <th class="uk-text-right">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td><a href="{{ route('account.orders.show', $order->id) }}">{{ $order->id }}</a></td>
                            <td>{{ $order->created_at->format('m/d/Y') }}</td>
                            <td class="uk-text-right">${{ $order->total->inDollars() }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection