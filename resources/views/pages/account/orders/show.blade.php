@extends('layouts.sub.account')

@section('title')
    Orders
@endsection

@section('breadcrumbs')
    <li><span>Account</span></li>
    <li><a href="{{ route('account.orders') }}">Orders</a></li>
    <li><span>{{ $order->id }}</span></li>
@endsection

@section('side-nav')
    @include('partials.navigation.account', ['active' => 'orders'])
@endsection

@section('page-content')

    <div class="uk-card uk-card-default uk-card-small">
        <div class="uk-card-header">
            <h3 class="uk-card-title">Order #{{ $order->id }}</h3>
        </div>
        <div class="uk-card-body">
            <h3>Sponsorships</h3>

            <order-sponsorships :sponsorships="{{ $order->sponsorships->toJson() }}" :paid="1"></order-sponsorships>

            <h3>Total</h3>
            <table class="uk-table uk-table-small uk-table-striped">
                <thead>
                <tr>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${{ $order->total->inDollars() }}</td>
                    </tr>
                </tbody>
            </table>

            <h3>Charges</h3>

            <table class="uk-table uk-table-striped uk-table-small">
                <thead>
                <tr>
                    <td>Date</td>
                    <td>Charge ID</td>
                    <td>Status</td>
                    <td>Amount</td>
                </tr>
                </thead>
                <tbody>
                @foreach($order->charges as $charge)
                    <tr>
                        <td>{{ $charge->created_at->format('m/d/y') }}</td>
                        <td>{{ $charge->charge_id }}</td>
                        <td>{{ $charge->status }}</td>
                        <td>{{ $charge->amount->inDollars() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection