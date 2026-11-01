@extends('admin.layout', ['title' => 'Foydalanuvchilar'])

@section('content')
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5"><input name="search" value="{{ request('search') }}" class="form-control" placeholder="Ism yoki email bo‘yicha qidirish"></div>
    <div class="col-auto"><button class="btn btn-outline-primary">Qidirish</button><a href="{{ route('admin.users.index') }}" class="btn btn-link">Tozalash</a></div>
</form>
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Foydalanuvchi</th><th>Rollar</th><th>Rollarni o‘zgartirish</th><th></th></tr></thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td><div class="fw-semibold">{{ $user->name }}</div><div class="small text-secondary">{{ $user->email }}</div><div class="small text-secondary">ID: {{ $user->id }}</div></td>
                    <td>@forelse($user->roles as $role)<span class="badge text-bg-secondary me-1">{{ $role->name }}</span>@empty<span class="text-secondary">Rol belgilanmagan</span>@endforelse</td>
                    <td>
                        <form action="{{ route('admin.users.roles', $user) }}" method="POST" class="d-flex gap-2 align-items-start">
                            @csrf @method('PUT')
                            <select name="roles[]" class="form-select form-select-sm" multiple required size="3" aria-label="Foydalanuvchi rollari">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @selected($user->roles->contains('id', $role->id))>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary">Saqlash</button>
                        </form>
                    </td>
                    <td class="text-end">
                        @if(!$user->is(auth()->user()))
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Foydalanuvchini o‘chirishni tasdiqlaysizmi?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">O‘chirish</button>
                            </form>
                        @else
                            <span class="small text-secondary">Sizning hisobingiz</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-secondary py-4">Foydalanuvchi topilmadi.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $users->links() }}</div>
@endsection
