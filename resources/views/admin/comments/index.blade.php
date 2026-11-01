@extends('admin.layout', ['title' => 'Izohlar'])

@section('content')
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Izoh</th><th>Muallif</th><th>Post</th><th>Sana</th><th></th></tr></thead>
            <tbody>
            @forelse($comments as $comment)
                <tr>
                    <td style="min-width:240px">{{ \Illuminate\Support\Str::limit($comment->body, 180) }}</td>
                    <td>{{ $comment->user?->name ?? 'O‘chirilgan foydalanuvchi' }}</td>
                    <td>{{ $comment->post?->title ?? 'O‘chirilgan post' }}</td>
                    <td>{{ $comment->created_at?->format('d.m.Y H:i') }}</td>
                    <td class="text-end">
                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Izohni o‘chirishni tasdiqlaysizmi?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">O‘chirish</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center text-secondary py-4">Izohlar mavjud emas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $comments->links() }}</div>
@endsection
