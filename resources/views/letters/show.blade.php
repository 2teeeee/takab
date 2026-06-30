<x-admin-layout :title="'جزئیات نامه: ' . $letter->subject" header="جزئیات نامه">
    <div class="container py-4">

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-light fw-bold">
                موضوع: {{ $letter->subject }}
            </div>
            <div class="card-body">
                <p><strong>فرستنده:</strong> {{ $letter->sender->name }}</p>
                <p><strong>گیرنده:</strong> {{ $letter->receiver->name }}</p>
                <p><strong>اولویت:</strong>
                    <span class="badge
                        @if($letter->priority == 'high') bg-danger
                        @elseif($letter->priority == 'medium') bg-warning
                        @else bg-success @endif">
                        {{ ucfirst($letter->priority) }}
                    </span>
                </p>
                <hr>
                <p>{{ $letter->body }}</p>

                @if ($letter->attachments->count())
                    <hr>
                    <h6>ضمائم:</h6>
                    <ul>
                        @foreach($letter->attachments as $attachment)
                            <li class="list-group-item d-flex justify-content-between align-items-center mb-1">
                                <a href="{{ route('admin.letters.attachments.download', $attachment) }}"
                                   class="text-decoration-none">
                                    <i class="bi bi-paperclip"></i>
                                    {{ $attachment->file_name }}
                                </a>

                                <form action="{{ route('admin.letters.attachments.destroy', $attachment) }}"
                                      method="POST"
                                      onsubmit="return confirm('آیا از حذف این فایل مطمئن هستید؟')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- افزودن ضمیمه جدید --}}
        <form action="{{ route('admin.letters.attachments.store', $letter) }}"
              method="POST"
              enctype="multipart/form-data"
              id="attachmentForm" class="mb-3 pb-2 border-bottom">
            @csrf

            <div class="mb-3">
                <label class="form-label">انتخاب فایل‌ها</label>
                <input type="file"
                       name="attachments[]"
                       class="form-control"
                       multiple
                       required>
            </div>

            <button type="submit" class="btn btn-success" id="submitBtn">
        <span class="btn-text">
            <i class="bi bi-paperclip"></i>
            افزودن ضمیمه
        </span>

                <span class="spinner-border spinner-border-sm d-none"
                      role="status"
                      aria-hidden="true"></span>
                <span class="loading-text d-none">در حال آپلود...</span>
            </button>
        </form>

        {{-- فرم ارجاع --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white fw-bold">
                ارجاع نامه
            </div>
            <div class="card-body">
                <form action="{{ route('admin.letters.refer', $letter) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="to_user_id" class="form-label">ارجاع به</label>
                        <select name="to_user_id" id="to_user_id" class="form-select" required>
                            <option value="">انتخاب کنید...</option>
                            @foreach($referableUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('to_user_id') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label for="note" class="form-label">توضیحات ارجاع</label>
                        <textarea name="note" id="note" rows="3" class="form-control"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">ارجاع</button>
                </form>
            </div>
        </div>

        {{-- تاریخچه ارجاعات --}}
        <div class="card shadow-sm">
            <div class="card-header bg-light fw-bold">
                تاریخچه ارجاعات
            </div>
            <div class="card-body">
                @if($references->count())
                    <table class="table table-bordered">
                        <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>از</th>
                            <th>به</th>
                            <th>یادداشت</th>
                            <th>تاریخ ارجاع</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($references as $ref)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $ref->from->name }}</td>
                                <td>{{ $ref->to->name }}</td>
                                <td>{{ $ref->note ?? '-' }}</td>
                                <td>{{ jdate($ref->created_at)->format('Y/m/d H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted">هنوز هیچ ارجاعی ثبت نشده است.</p>
                @endif
            </div>
        </div>

    </div>

    <script>
        document.getElementById('attachmentForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');

            btn.disabled = true;

            btn.querySelector('.btn-text').classList.add('d-none');
            btn.querySelector('.spinner-border').classList.remove('d-none');
            btn.querySelector('.loading-text').classList.remove('d-none');
        });
    </script>

</x-admin-layout>
