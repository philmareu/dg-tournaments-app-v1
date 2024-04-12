<p>{{ $user->name }} - {{ $user->email }}</p>

<p>Requested a refund.</p>

<ul>
    <li>{{ $transfer->id }}</li>
    <li>{{ $transfer->tr_id }}</li>
    <li>Requested amount: {{ $amount }}</li>
</ul>