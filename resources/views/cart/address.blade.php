<x-main-layout>
    <div class="container py-4">
        <h4>ثبت آدرس</h4>

        <form action="{{ route('cart.pay') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="address" class="form-label">آدرس کامل</label>
                <textarea name="address" id="address" rows="4" class="form-control" required></textarea>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success">
                    پرداخت و انتقال به درگاه
                </button>
            </div>
        </form>
    </div>
</x-main-layout>
