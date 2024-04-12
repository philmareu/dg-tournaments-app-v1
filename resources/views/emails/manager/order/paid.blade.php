@component('mail::message')
# New Order

## Order #{{ $transfer->id }} - {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transfer->created_at)->format('M d, Y') }}

### Customer
{{ $transfer->order->first_name }} {{ $transfer->order->last_name }}<br>
{{ $transfer->order->email }}

### Sponsorships

|Title|Cost|
|----------|
@foreach($transfer->sponsorships as $sponsorship)
|{{ $sponsorship->sponsorship->title }}|${{ $sponsorship->cost->formatted() }}|
@endforeach

<br>

### Charge

|ID|Total|
|----------|
|{{ $transfer->tr_id }}|${{ $transfer->total->formatted() }}|

<br>

@component('mail::button', ['url' => url('manage/' . $transfer->tournament_id . '/order/' . $transfer->id)])
    View Order
@endcomponent

{{ config('app.name') }}
@endcomponent
