@extends('layouts.sub.manage.tournament')

@section('title')
    Order
@endsection

@section('side-nav')
    @include('partials.navigation.manage.tournament', ['active' => 'orders'])
@endsection

@section('page-content')
    @parent

    <div class="uk-card uk-card-default uk-card-small">
        <div class="uk-card-header">
            <h3 class="uk-card-title">Order #{{ $transfer->id }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transfer->created_at)->format('M d, Y') }}</h3>
        </div>

        <div class="uk-card-body">
            <h3>Customer</h3>
            {{ $transfer->order->first_name }} {{ $transfer->order->last_name }}<br>
            {{ $transfer->order->email }}

            <h3>Sponsorships</h3>

            <table class="uk-table uk-table-small uk-table-striped">
                <thead>
                <tr>
                    <th>Title</th>
                    <th class="uk-text-right">Cost</th>
                </tr>
                </thead>
                <tbody>
                @foreach($transfer->sponsorships as $sponsorship)
                    <tr>
                        <td>{{ $sponsorship->sponsorship->title }}</td>
                        <td class="uk-text-right">${{ $sponsorship->cost->formatted() }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h3>Charge</h3>

            <table class="uk-table uk-table-small uk-table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th class="uk-text-right">Total</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{ $transfer->id }}</td>
                    <td class="uk-text-right">${{ $transfer->total->formatted() }}</td>
                </tr>
                </tbody>
            </table>

            <h3>Refunds</h3>
            <refund :transfer="{{ $transfer->toJson() }}"></refund>
        </div>
    </div>
@endsection