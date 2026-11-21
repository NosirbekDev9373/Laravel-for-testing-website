@extends('admin.layout', ['title' => 'Foydalanuvchilar'])

@section('content')
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

<form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-3 align-items-end">
    <div class="col-md-5">
        <label for="user-search" class="form-label">Qidiruv</label>
        <input id="user-search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Ism yoki email bo‘yicha qidirish">
    </div>
    <div class="col-md-3">
        <label for="user-role" class="form-label">Rol</label>
        <select id="user-role" name="role" class="form-select">
            <option value="">Barchasi</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" @selected($roleId === $role->id)>{{ $role->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Qidirish</button>
        @if($search !== '' || $roleId)
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Tozalash</a>
        @endif
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Foydalanuvchilar</strong>
        <span class="small text-secondary">{{ $users->total() }} ta</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Foydalanuvchi</th>
                    <th>Postlar</th>
                    <th>Izohlar</th>
                    <th>Rollar</th>
                    <th>Rollarni o‘zgartirish</th>
                    <th class="text-end">Amallar</th>
                </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div class="fw-semibold">{{ $user->name }}</div>
                        <div class="small text-secondary">{{ $user->email }}</div>
                        <div class="small text-secondary">ID: {{ $user->id }}</div>
                    </td>
                    <td>{{ $user->posts_count }}</td>
                    <td>{{ $user->comments_count }}</td>
                    <td>
                        @forelse($user->roles as $role)
                            <span class="badge bg-light text-dark me-1">{{ $role->name }}</span>
                        @empty
                            <span class="text-secondary">Rol belgilanmagan</span>
                        @endforelse
                    </td>
                    <td>
                        <form action="{{ route('admin.users.roles', $user) }}" method="POST" class="d-flex gap-2 align-items-start">
                            @csrf @method('PUT')
                            <select name="roles[]" class="form-select form-select-sm @error('roles') is-invalid @enderror" multiple required size="3" aria-label="Foydalanuvchi rollari">
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" @selected($user->roles->contains('id', $role->id))>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary">Saqlash</button>
                        </form>
                    </td>
                    <td class="text-end">
                        @if(! $user->is(auth()->user()))
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
                <tr><td colspan="6" class="text-center text-secondary py-4">{{ $search !== '' || $roleId ? 'Qidiruv bo‘yicha foydalanuvchi topilmadi.' : 'Foydalanuvchi mavjud emas.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $users->links() }}</div>
</div>
@endsection
