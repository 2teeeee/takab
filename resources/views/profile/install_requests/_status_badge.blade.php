@props(['status'])

@php
    $map = [
        'pending'   => ['text' => 'در انتظار بررسی', 'class' => 'bg-warning text-dark'],
        'scheduled' => ['text' => 'زمان‌بندی شد',     'class' => 'bg-info text-white'],
        'installed' => ['text' => 'نصب شده',          'class' => 'bg-success text-white'],
        'serviced'  => ['text' => 'سرویس شده',        'class' => 'bg-primary text-white'],
        'cancelled' => ['text' => 'لغو شده',          'class' => 'bg-danger text-white'],
    ];

    $item = $map[$status] ?? ['text' => $status, 'class' => 'bg-secondary text-white'];
@endphp

<span class="badge {{ $item['class'] }} px-3 py-2 rounded-pill">
    {{ $item['text'] }}
</span>
<?php
