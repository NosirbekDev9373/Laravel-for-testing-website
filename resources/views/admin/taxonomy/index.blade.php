@extends('admin.layout', ['title' => $pageTitle])

@section('content')
@php
    $label = $pageTitle === 'Kategoriyalar' ? 'kategoriya' : 'teg';
@endphp

@if($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-1">Formada xatolar bor:</div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="GET" action="{{ route($indexRoute) }}" class="d-flex flex-wrap gap-2 align-items-end mb-3">
    <div class="flex-grow-1">
        <label for="taxonomy-search" class="form-label">Qidiruv</label>
        <input id="taxonomy-search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Nomi bo‘yicha qidirish">
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Qidirish</button>
    @if($search !== '')
        <a href="{{ route($indexRoute) }}" class="btn btn-outline-secondary">Tozalash</a>
    @endif
</form>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">Yangi {{ $label }}</div>
            <div class="card-body">
                <form method="POST" action="{{ route($storeRoute) }}">
                    @csrf
                    <label for="taxonomy-name" class="form-label">Nomi</label>
                    <input id="taxonomy-name" name="name" class="form-control @error('name') is-invalid @enderror" required maxlength="255" value="{{ old('name') }}">
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <button class="btn btn-primary mt-3">Saqlash</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <strong>{{ $pageTitle }}</strong>
                <span class="small text-secondary">{{ $items->total() }} ta</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light"><tr><th>ID</th><th>Nomi</th><th>Postlar soni</th><th class="text-end">Amallar</th></tr></thead>
                    <tbody>
                    @forelse($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>
                                <form method="POST" action="{{ route($updateRoute, $item) }}" class="d-flex gap-1">
                                    @csrf @method('PUT')
                                    <input name="name" class="form-control form-control-sm" value="{{ $item->name }}" required maxlength="255">
                                    <button class="btn btn-sm btn-outline-primary">Saqlash</button>
                                </form>
                            </td>
                            <td>{{ $item->post_count ?? $item->posts_count ?? 0 }}</td>
                            <td class="text-end">
                                <form method="POST" action="{{ route($deleteRoute, $item) }}" onsubmit="return confirm('O‘chirishni tasdiqlaysizmi?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">O‘chirish</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">{{ $search !== '' ? 'Qidiruv bo‘yicha topilmadi.' : 'Ma’lumot mavjud emas.' }}</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $items->links() }}</div>
        </div>
    </div>
</div>
@endsection
