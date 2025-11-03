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
                            <li>
                                <a href="{{ route('admin.letters.attachments.download', $attachment) }}" class="text-decoration-none">
                                    <i class="bi bi-paperclip"></i> {{ $attachment->file_name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

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
</x-admin-layout>
