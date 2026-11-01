<x-layouts.main>
    <x-slot:title>Bildirishnomalar</x-slot:title>
    <x-page-header>Bildirishnomalar</x-page-header>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-12">

                @if(auth()->user()->notifications->count() > 0)

                    <div class="d-flex justify-content-between mb-4">
                        <h4>Barcha bildirishnomalar</h4>
                        <form action="{{ route('notifications.readAll') }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-dark">
                                Hammasini o'qilgan deb belgilash
                            </button>
                        </form>
                    </div>

                    @foreach(auth()->user()->notifications as $notification)
                        <div class="p-4 mb-3 rounded shadow-sm
                            {{ $notification->read_at ? 'bg-white' : 'bg-light border-start border-danger border-3' }}">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi-bell me-2"></i>
                                    <strong>{{ $notification->data['message'] }}</strong>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="text-muted small">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                    @if(!$notification->read_at)
                                        <form action="{{ route('notifications.read', $notification->id) }}"
                                              method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-success">
                                                O'qildi
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                @else
                    <div class="alert alert-info">
                        <i class="bi-bell-slash me-2"></i>
                        Hozircha bildirishnoma yo'q.
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-layouts.main>
