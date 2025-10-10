<x-profile-layout title="اطلاعات کاربر">
    <ul class="list-group">
        <li class="list-group-item"><strong>نام:</strong> {{ $user->name }}</li>
        <li class="list-group-item"><strong>ایمیل:</strong> {{ $user->email }}</li>
        <li class="list-group-item"><strong>موبایل:</strong> {{ $user->mobile }}</li>
    </ul>
</x-profile-layout>
