@props(['payment_type'])

@php
    $map = [
        'online'            => ['text' => 'آنلاین',          'class' => 'bg-dark text-light'],
        'cash'              => ['text' => 'نقدی',           'class' => 'bg-dark text-light'],
        'wallet'            => ['text' => 'کیف پول',        'class' => 'bg-dark text-light'],
        'cash_on_delivery'  => ['text' => 'پرداخت در محل',  'class' => 'bg-dark text-light'],
    ];

    $item = $map[$payment_type] ?? ['text' => $payment_type, 'class' => 'bg-dark text-light'];
@endphp

<span class="badge {{ $item['class'] }}">
    {{ $item['text'] }}
</span>
<?php
