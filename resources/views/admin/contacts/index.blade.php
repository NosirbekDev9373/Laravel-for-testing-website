@extends('admin.layout', ['title' => 'Kontakt xabarlari'])

@section('content')
<form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-2 mb-3 align-items-end">
    <div class="col-md-6">
        <label for="contact-search" class="form-label">Qidiruv</label>
        <input id="contact-search" type="search" name="search" value="{{ $search }}" class="form-control" placeholder="Ism, email, xizmat yoki xabar bo‘yicha qidirish">
    </div>
    <div class="col-md-3">
        <label for="contact-status" class="form-label">Holat</label>
        <select id="contact-status" name="status" class="form-select">
            <option value="">Barchasi</option>
            <option value="unread" @selected($status === 'unread')>O‘qilmagan</option>
            <option value="read" @selected($status === 'read')>O‘qilgan</option>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Qidirish</button>
        @if($search !== '' || $status)
            <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">Tozalash</a>
        @endif
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>Kontakt xabarlari</strong>
        <span class="small text-secondary">{{ $contacts->total() }} ta · {{ $unreadCount }} ta o‘qilmagan</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Yuboruvchi</th>
                    <th>Xizmat turi</th>
                    <th>Xabar</th>
                    <th>Sana</th>
                    <th>Holat</th>
                    <th class="text-end">Amallar</th>
                </tr>
            </thead>
            <tbody>
            @forelse($contacts as $contact)
                <tr class="{{ $contact->read_at ? '' : 'table-light' }}">
                    <td>
                        <div class="fw-semibold">{{ $contact->name }}</div>
                        <div class="small text-secondary">{{ $contact->email }}</div>
                    </td>
                    <td>{{ $contact->service_type }}</td>
                    <td style="min-width:220px" title="{{ $contact->message }}">{{ \Illuminate\Support\Str::limit($contact->message, 100) }}</td>
                    <td>{{ $contact->created_at?->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($contact->read_at)
                            <span class="badge bg-light text-dark">O‘qilgan</span>
                        @else
                            <span class="badge bg-light text-dark">O‘qilmagan</span>
                        @endif
                    </td>
                    <td class="text-end text-nowrap">
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="btn btn-sm btn-outline-primary">Ko‘rish</a>
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" class="d-inline" onsubmit="return confirm('Xabarni o‘chirishni tasdiqlaysizmi?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">O‘chirish</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-4">{{ $search !== '' || $status ? 'Qidiruv bo‘yicha xabar topilmadi.' : 'Hozircha xabar yo‘q.' }}</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $contacts->links() }}</div>
</div>
@endsection
