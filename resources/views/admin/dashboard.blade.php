@extends('admin.layout', ['title' => 'Dashboard'])

@section('content')
<div class="row g-3 mb-4">
    @foreach([
        ['Foydalanuvchilar', $usersCount, 'bi-people', 'primary', 'admin.users.index'],
        ['Postlar', $postsCount, 'bi-file-earmark-text', 'success', 'admin.posts.index'],
        ['Kategoriyalar', $categoriesCount, 'bi-folder', 'warning', 'admin.categories.index'],
        ['Teglar', $tagsCount, 'bi-tags', 'info', 'admin.tags.index'],
        ['Izohlar', $commentsCount, 'bi-chat-left-text', 'danger', 'admin.comments.index'],
    ] as [$label, $count, $icon, $color, $route])
        <div class="col-6 col-xl">
            <a class="text-decoration-none text-reset" href="{{ route($route) }}">
                <div class="card stat-card h-100">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div><div class="text-secondary small">{{ $label }}</div><div class="fs-3 fw-bold">{{ $count }}</div></div>
                        <span class="rounded-circle bg-{{ $color }}-subtle text-{{ $color }} p-3"><i class="bi {{ $icon }} fs-4"></i></span>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>Oxirgi postlar</strong>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-primary">Barchasi</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead><tr><th>Sarlavha</th><th>Muallif</th><th>Kategoriya</th><th>Sana</th></tr></thead>
                    <tbody>
                    @forelse($latestPosts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->user?->name ?? '—' }}</td>
                            <td>{{ $post->category?->name ?? '—' }}</td>
                            <td>{{ $post->created_at?->format('d.m.Y') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">Hozircha post yo‘q.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><strong>Yangi foydalanuvchilar</strong></div>
            <ul class="list-group list-group-flush">
                @forelse($latestUsers as $user)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div><div class="fw-semibold">{{ $user->name }}</div><div class="small text-secondary">{{ $user->email }}</div></div>
                        <span class="small text-secondary">{{ $user->created_at?->format('d.m.Y') }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-secondary">Foydalanuvchilar topilmadi.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
