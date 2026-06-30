@props(['status'])

@php
    $map = [
        'pending'   => ['text' => 'در انتظار بررسی',    'class' => 'bg-warning text-dark'],
        'referred'   => ['text' => 'ارجاع داده شده',    'class' => 'bg-warning text-dark'],
        'scheduled' => ['text' => 'زمان‌بندی شد',        'class' => 'bg-info text-white'],
        'new' => ['text' => 'جدید',        'class' => 'bg-info text-white'],
        'low' => ['text' => 'کم',        'class' => 'bg-info text-white'],
        'processing' => ['text' => 'در حال آماده‌سازی',  'class' => 'bg-info text-white'],
        'installed' => ['text' => 'نصب شده',            'class' => 'bg-success text-white'],
        'read' => ['text' => 'خوانده شده',            'class' => 'bg-success text-white'],
        'shipping' => ['text' => 'ارسال شده',           'class' => 'bg-success text-white'],
        'delivered' => ['text' => 'تحویل شده',          'class' => 'bg-success text-white'],
        'medium' => ['text' => 'معمولی',          'class' => 'bg-success text-white'],
        'paid'      => ['text' => 'پرداخت شده',         'class' => 'bg-info text-white'],
        'serviced'  => ['text' => 'سرویس شده',          'class' => 'bg-primary text-white'],
        'closed'  => ['text' => 'اتمام یافته',          'class' => 'bg-primary text-white'],
        'cancelled' => ['text' => 'لغو شده',            'class' => 'bg-danger text-white'],
        'failed'    => ['text' => 'تکمیل نشده',         'class' => 'bg-danger text-white'],
        'high'    => ['text' => 'بالا',         'class' => 'bg-danger text-white'],
    ];

    $item = $map[$status] ?? ['text' => $status, 'class' => 'bg-secondary text-white'];
@endphp

<span class="badge {{ $item['class'] }} px-3 py-2 rounded-pill">
    {{ $item['text'] }}
</span>
<?php
