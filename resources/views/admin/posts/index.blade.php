@extends('admin.layout', ['title' => 'Postlarni boshqarish'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="text-secondary mb-0">Postlarni yaratish, tahrirlash va o‘chirish. Sizning rolingizga ruxsat etilgan amallar ko‘rsatiladi.</p>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Yangi post</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form action="{{ route('admin.posts.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-lg-5">
                <label for="admin-post-search" class="form-label">Qidiruv</label>
                <input id="admin-post-search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Sarlavha, matn, muallif yoki email">
            </div>
            <div class="col-lg-3">
                <label for="admin-post-category" class="form-label">Kategoriya</label>
                <select id="admin-post-category" name="category" class="form-select">
                    <option value="">Barchasi</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected($categoryId === $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Qidirish</button>
                @if($search !== '' || $categoryId)
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Tozalash</a>
                @endif
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-2">
    <div class="small text-secondary">{{ $posts->total() }} ta post topildi</div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>ID</th><th>Sarlavha</th><th>Muallif</th><th>Kategoriya</th><th>Sana</th><th class="text-end">Amallar</th></tr></thead>
            <tbody>
            @forelse($posts as $post)
                <tr>
                    <td>{{ $post->id }}</td>
                    <td><div class="fw-semibold">{{ $post->title }}</div><div class="small text-secondary">{{ \Illuminate\Support\Str::limit($post->short_content, 65) }}</div></td>
                    <td>{{ $post->user?->name ?? '—' }}</td>
                    <td>{{ $post->category?->name ?? '—' }}</td>
                    <td>{{ $post->created_at?->format('d.m.Y') }}</td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-outline-secondary" target="_blank">Ko‘rish</a>
                        @can('update', $post)
                            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">Tahrirlash</a>
                        @endcan
                        @can('delete', $post)
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline" onsubmit="return confirm('Postni o‘chirishni tasdiqlaysizmi?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">O‘chirish</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">{{ $search !== '' || $categoryId ? 'Qidiruv bo‘yicha post topilmadi.' : 'Postlar mavjud emas.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $posts->links() }}</div>
@endsection
