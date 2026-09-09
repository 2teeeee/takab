@props(['payment_status'])

@php
    $map = [
        'unpaid'    => ['text' => 'پرداخت نشده',    'class' => 'bg-warning text-dark'],
        'pending'   => ['text' => 'در حال پرداخت',  'class' => 'bg-info text-white'],
        'paid'      => ['text' => 'پرداخت شده',     'class' => 'bg-success text-white'],
        'failed'    => ['text' => 'پرداخت نشده',    'class' => 'bg-dark text-light'],
        'refunded'  => ['text' => 'پرداخت مجدد',    'class' => 'bg-primary text-white'],
    ];

    $item = $map[$payment_status] ?? ['text' => $payment_status, 'class' => 'bg-secondary text-white'];
@endphp

<span class="badge {{ $item['class'] }}">
    {{ $item['text'] }}
</span>
<?php
