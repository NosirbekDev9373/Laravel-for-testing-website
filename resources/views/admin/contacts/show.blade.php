@extends('admin.layout', ['title' => 'Xabar'])

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Orqaga</a>
    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Xabarni o‘chirishni tasdiqlaysizmi?')">
        @csrf @method('DELETE')
        <button class="btn btn-outline-danger">O‘chirish</button>
    </form>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <strong>{{ $contact->name }}</strong>
        @if($contact->read_at)
            <span class="badge bg-light text-dark">O‘qilgan: {{ $contact->read_at->format('d.m.Y H:i') }}</span>
        @else
            <span class="badge bg-light text-dark">O‘qilmagan</span>
        @endif
    </div>
    <div class="card-body">
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="text-secondary small">Email</div>
                <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
            </div>
            <div class="col-md-3">
                <div class="text-secondary small">Xizmat turi</div>
                <div>{{ $contact->service_type }}</div>
            </div>
            <div class="col-md-3">
                <div class="text-secondary small">Sana</div>
                <div>{{ $contact->created_at?->format('d.m.Y H:i') }}</div>
            </div>
            @if($contact->ip_address)
                <div class="col-md-6">
                    <div class="text-secondary small">IP manzil</div>
                    <div>{{ $contact->ip_address }}</div>
                </div>
            @endif
        </div>
        <div class="border-top pt-3">
            <div class="text-secondary small mb-2">Xabar</div>
            <div class="fs-6" style="white-space: pre-line">{{ $contact->message }}</div>
        </div>
    </div>
</div>
@endsection
