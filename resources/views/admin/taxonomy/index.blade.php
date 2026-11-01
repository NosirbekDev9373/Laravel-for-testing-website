@extends('admin.layout', ['title' => $pageTitle])

@section('content')
<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Yangi {{ $pageTitle === 'Kategoriyalar' ? 'kategoriya' : 'teg' }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route($storeRoute) }}">
                    @csrf
                    <label class="form-label">Nomi</label>
                    <input name="name" class="form-control" required maxlength="255" value="{{ old('name') }}">
                    <button class="btn btn-primary mt-3">Saqlash</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>ID</th><th>Nomi</th><th>Postlar soni</th><th></th></tr></thead>
                    <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td><td>{{ $item->name }}</td>
                            <td>{{ $item->post_count ?? $item->posts_count ?? 0 }}</td>
                            <td class="text-end">
                                <form method="POST" action="{{ route($deleteRoute, $item) }}" onsubmit="return confirm('O‘chirishni tasdiqlaysizmi?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">O‘chirish</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">Ma’lumot mavjud emas.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
