<x-admin-layout title="لیست نامه‌ها" header="لیست نامه‌ها">
    <div class="container py-4 px-0">

        <a href="{{ route('admin.letters.create') }}" class="btn btn-sm btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> ایجاد نامه جدید
        </a>

        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.letters.index') }}"
                       class="btn btn-sm {{ request('tab')=='' ? 'btn-primary':'btn-outline-primary' }}">
                        همه
                    </a>

                    <a href="{{ route('admin.letters.index',['tab'=>'received']) }}"
                       class="btn btn-sm {{ request('tab')=='received' ? 'btn-primary':'btn-outline-primary' }}">
                        دریافتی
                    </a>

                    <a href="{{ route('admin.letters.index',['tab'=>'sent']) }}"
                       class="btn btn-sm {{ request('tab')=='sent' ? 'btn-primary':'btn-outline-primary' }}">
                        ارسالی
                    </a>

                    <a href="{{ route('admin.letters.index',['tab'=>'unread']) }}"
                       class="btn btn-sm {{ request('tab')=='unread' ? 'btn-danger':'btn-outline-danger' }}">
                        خوانده نشده
                    </a>

                    <a href="{{ route('admin.letters.index',['tab'=>'read']) }}"
                       class="btn btn-sm {{ request('tab')=='read' ? 'btn-success':'btn-outline-success' }}">
                        خوانده شده
                    </a>

                    <a href="{{ route('admin.letters.index',['tab'=>'referred']) }}"
                       class="btn btn-sm {{ request('tab')=='referred' ? 'btn-warning':'btn-outline-warning' }}">
                        ارجاع شده
                    </a>
                </div>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.letters.index') }}" class="mb-3">

            <input type="hidden" name="tab" value="{{ request('tab') }}">

            <div class="input-group">

                <input type="text"
                       name="search"
                       class="form-control"
                       value="{{ request('search') }}"
                       placeholder="جستجو در موضوع، متن، فرستنده و گیرنده">

                <button class="btn btn-primary">
                    <i class="bi bi-search"></i>
                </button>

            </div>

        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>موضوع</th>
                    <th>فرستنده</th>
                    <th>گیرنده</th>
                    <th>اولویت</th>
                    <th>وضعیت</th>
                    <th>تاریخ</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($letters as $letter)

                    @php
                        $receiver = $letter->receiverItems->firstWhere('user_id', auth()->id());

                        $isReceived = !is_null($receiver);

                        $isUnread = $receiver && $receiver->status === 'new';

                        $isRead = $receiver && $receiver->status === 'read';
                    @endphp

                    <tr class="{{ $isUnread ? 'table-warning fw-bold' : '' }}">
                        <td>{{ $loop->iteration + ($letters->currentPage() - 1) * $letters->perPage() }}</td>

                        <td>
                            @if($isUnread)
                                <i class="bi bi-envelope-fill text-danger me-1"></i>
                            @elseif($isRead)
                                <i class="bi bi-envelope-open text-success me-1"></i>
                            @else
                                <i class="bi bi-send text-secondary me-1"></i>
                            @endif

                            {{ $letter->subject }}

                            @php
                                $reference = $letter->references
                                    ->where('to_user_id', auth()->id())
                                    ->sortByDesc('created_at')
                                    ->first();
                            @endphp

                            @if($reference)
                                <div class="small text-primary mt-1">
                                    <i class="bi bi-arrow-return-left"></i>
                                    {{ $reference->note }}
                                </div>
                            @endif
                        </td>

                        <td>{{ $letter->sender->name }}</td>

                        <td>
                            @foreach($letter->receiverItems as $item)
                                <span class="badge bg-secondary">
                                    {{ $item->user->name }}
                                </span>
                            @endforeach
                        </td>

                        <td>
                            <x-status_badge status="{{ $letter->priority }}" />
                        </td>

                        <td>
                            @if($isReceived)
                                @if($isUnread)
                                    <span class="badge bg-danger">
                                        خوانده نشده
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        خوانده شده
                                    </span>
                                @endif
                            @else
                                <span class="badge bg-secondary">
                                    ارسالی
                                </span>
                            @endif
                        </td>

                        <td>{{ jdate($letter->created_at->setTimezone('Asia/Tehran'))->format('Y/m/d H:i') }}</td>

                        <td>
                            <a href="{{ route('admin.letters.show', $letter) }}"
                               class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i>
                                مشاهده
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">هیچ نامه‌ای یافت نشد.</td></tr>
                @endforelse
                </tbody>
            </table>

            {{ $letters->links() }}
        </div>
    </div>
</x-admin-layout>
