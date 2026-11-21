@extends('admin.layout', ['title' => 'Izohlar'])

@section('content')
<form method="GET" action="{{ route('admin.comments.index') }}" class="d-flex flex-wrap gap-2 align-items-end mb-3">
    <div class="flex-grow-1">
        <label for="comment-search" class="form-label">Qidiruv</label>
        <input id="comment-search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Izoh, muallif yoki post bo‘yicha qidirish">
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Qidirish</button>
    @if($search !== '')
        <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary">Tozalash</a>
    @endif
</form>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Izohlar</strong>
        <span class="small text-secondary">{{ $comments->total() }} ta</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Izoh</th><th>Muallif</th><th>Post</th><th>Sana</th><th class="text-end">Amallar</th></tr></thead>
            <tbody>
            @forelse($comments as $comment)
                <tr>
                    <td style="min-width:240px" title="{{ $comment->body }}">{{ \Illuminate\Support\Str::limit($comment->body, 180) }}</td>
                    <td>{{ $comment->user?->name ?? 'O‘chirilgan foydalanuvchi' }}</td>
                    <td>
                        @if($comment->post)
                            <a href="{{ route('posts.show', $comment->post) }}" target="_blank">{{ $comment->post->title }}</a>
                        @else
                            O‘chirilgan post
                        @endif
                    </td>
                    <td>{{ $comment->created_at?->format('d.m.Y H:i') }}</td>
                    <td class="text-end">
                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Izohni o‘chirishni tasdiqlaysizmi?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">O‘chirish</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-secondary py-4">{{ $search !== '' ? 'Qidiruv bo‘yicha izoh topilmadi.' : 'Izohlar mavjud emas.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $comments->links() }}</div>
</div>
@endsection
