<x-main-layout>
    <div class="container py-5">
        <div class="border rounded-md-4 shadow px-4 py-5">
            <h4 class="mb-4">{{ $page->title }}</h4>
            <div class="page-content">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</x-main-layout>
