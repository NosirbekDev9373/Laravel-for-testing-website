<!doctype html>
<html lang="uz">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Admin panel' }} | Clean Work</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-icons.css') }}" rel="stylesheet">
    <style>
        body { background:#f4f6fa; color:#202938; }
        .admin-sidebar { min-height:100vh; background:#172033; }
        .admin-sidebar a { display:block; color:#dce3ef; text-decoration:none; padding:.75rem 1rem; border-radius:.5rem; margin:.15rem 0; }
        .admin-sidebar a:hover, .admin-sidebar a.active { background:#2b3852; color:#fff; }
        .admin-brand { color:#fff; font-size:1.2rem; font-weight:700; padding:1.2rem 1rem; }
        .admin-main { min-width:0; }
        .stat-card { border:0; box-shadow:0 4px 18px rgba(20,35,60,.06); }
        .table td, .table th { vertical-align:middle; }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <aside class="col-md-3 col-lg-2 admin-sidebar p-3">
            <div class="admin-brand"><i class="bi bi-speedometer2 me-2"></i>Clean Work Admin</div>
            <nav>
                <a href="{{ route('admin.dashboard') }}"><i class="bi bi-grid me-2"></i>Dashboard</a>
                <a href="{{ route('admin.posts.index') }}"><i class="bi bi-file-earmark-text me-2"></i>Postlar</a>
                <a href="{{ route('admin.categories.index') }}"><i class="bi bi-folder me-2"></i>Kategoriyalar</a>
                <a href="{{ route('admin.tags.index') }}"><i class="bi bi-tags me-2"></i>Teglar</a>
                <a href="{{ route('admin.comments.index') }}"><i class="bi bi-chat-left-text me-2"></i>Izohlar</a>
                <a href="{{ route('admin.users.index') }}"><i class="bi bi-people me-2"></i>Foydalanuvchilar</a>
                <hr class="border-secondary">
                <a href="{{ route('main') }}"><i class="bi bi-box-arrow-up-right me-2"></i>Saytni ko‘rish</a>
                <form action="{{ route('logout') }}" method="POST" class="px-2 mt-3">
                    @csrf
                    <button class="btn btn-outline-light w-100" type="submit"><i class="bi bi-box-arrow-right me-2"></i>Chiqish</button>
                </form>
            </nav>
        </aside>
        <main class="col-md-9 col-lg-10 admin-main p-3 p-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 mb-1">{{ $title ?? 'Dashboard' }}</h1>
                    <div class="text-secondary small">Tizim boshqaruvi</div>
                </div>
                <div class="text-end">
                    <div class="fw-semibold">{{ auth()->user()->name }}</div>
                    <span class="badge text-bg-danger">Administrator</span>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Yopish"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <div class="fw-semibold mb-1">Xatolarni tekshiring:</div>
                    <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
