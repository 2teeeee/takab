<x-main-layout>
    <div class="container py-4">
        <h4>لیست کاربران</h4>

        <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">افزودن کاربر جدید</a>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            <thead>
            <tr>
                <th>#</th>
                <th>نام</th>
                <th>ایمیل</th>
                <th>نقش‌ها</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-warning">ویرایش</a>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('آیا از حذف کاربر اطمینان دارید؟')">حذف</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">هیچ کاربری یافت نشد.</td></tr>
            @endforelse
            </tbody>
        </table>

        {{ $users->links() }}
    </div>
</x-main-layout>
