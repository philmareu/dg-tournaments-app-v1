@component('mail::message')
# Order Confirmation

## Information

Order #{{ $order->id }}<br>
{{ $order->first_name }} {{ $order->last_name }}<br>
{{ $order->email }}

## Sponsorships

@foreach($order->sponsorships as $sponsorship)
{{ $sponsorship->sponsorship->tournament->name }} / {{ $sponsorship->sponsorship->title }} - ${{ $sponsorship->cost->formatted() }}
@endforeach

## Total Charge
@foreach($order->charges as $charge)
${{ $charge->amount->formatted() }}
@endforeach

{{ config('app.name') }}
@endcomponent
