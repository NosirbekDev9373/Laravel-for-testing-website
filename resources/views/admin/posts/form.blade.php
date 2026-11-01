@extends('admin.layout', ['title' => $editing ? 'Postni tahrirlash' : 'Yangi post'])

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST"
              action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if($editing) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label class="form-label">Sarlavha *</label>
                    <input name="title" class="form-control" required maxlength="255" value="{{ old('title', $post->title) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kategoriya *</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Tanlang</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Qisqa mazmun *</label>
                    <textarea name="short_content" class="form-control" rows="3" required>{{ old('short_content', $post->short_content) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label">To‘liq matn *</label>
                    <textarea name="content" class="form-control" rows="3" required>{{ old('content', $post->content) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Narx *</label>
                    <input type="number" step="0.01" min="0" name="price" class="form-control" required value="{{ old('price', $post->price ?? 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Davomiylik (ixtiyoriy)</label>
                    <input type="number" min="0" name="duration" class="form-control" value="{{ old('duration', $post->duration) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Teglar</label>
                    <select name="tags[]" class="form-select" multiple size="3">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', $editing ? $post->tags->pluck('id')->all() : [])))>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Bir nechta teg tanlash uchun Ctrl (Mac’da Command) ni bosing.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Asosiy rasm {{ $editing ? '(almashtirish ixtiyoriy)' : '' }}</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    @if($post->image)<div class="form-text">Hozirgi rasm: {{ $post->image }}</div>@endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Hover rasmi</label>
                    <input type="file" name="image_hover" class="form-control" accept="image/*">
                    @if($post->image_hover)<div class="form-text">Hozirgi rasm: {{ $post->image_hover }}</div>@endif
                </div>
                <div class="col-md-4">
                    <label class="form-label">Galereya rasmlari</label>
                    <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                    @if($editing && $post->gallery_images)<div class="form-text">{{ count($post->gallery_images) }} ta mavjud rasm. Yangi galereya yuklansa, eskilari almashtiriladi.</div>@endif
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">{{ $editing ? 'Saqlash' : 'Post yaratish' }}</button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Bekor qilish</a>
            </div>
        </form>
    </div>
</div>
@endsection
