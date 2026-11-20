@extends('admin.layout', ['title' => $editing ? 'Postni tahrirlash' : 'Yangi post'])

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
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

        <form method="POST"
              action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
              enctype="multipart/form-data">
            @csrf
            @if($editing) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-md-8">
                    <label for="post-title" class="form-label">Sarlavha *</label>
                    <input id="post-title" name="title" class="form-control @error('title') is-invalid @enderror" required maxlength="255" value="{{ old('title', $post->title) }}">
                    @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="post-category" class="form-label">Kategoriya *</label>
                    <select id="post-category" name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">Tanlang</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="post-short-content" class="form-label">Qisqa mazmun *</label>
                    <textarea id="post-short-content" name="short_content" class="form-control @error('short_content') is-invalid @enderror" rows="3" maxlength="1000" required>{{ old('short_content', $post->short_content) }}</textarea>
                    @error('short_content')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label for="post-content" class="form-label">To‘liq matn *</label>
                    <textarea id="post-content" name="content" class="form-control @error('content') is-invalid @enderror" rows="3" required>{{ old('content', $post->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="post-price" class="form-label">Narx (so‘m) *</label>
                    <input id="post-price" type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" required value="{{ old('price', $post->price ?? 0) }}">
                    @error('price')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="post-duration" class="form-label">Davomiylik (ixtiyoriy)</label>
                    <input id="post-duration" type="number" min="0" step="1" name="duration" class="form-control @error('duration') is-invalid @enderror" value="{{ old('duration', $post->duration) }}">
                    @error('duration')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="post-tags" class="form-label">Teglar</label>
                    <select id="post-tags" name="tags[]" class="form-select @error('tags') is-invalid @enderror" multiple size="3">
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" @selected(in_array($tag->id, (array) old('tags', $editing ? $post->tags->pluck('id')->all() : [])))>{{ $tag->name }}</option>
                        @endforeach
                    </select>
                    @error('tags')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @error('tags.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Bir nechta teg tanlash uchun Ctrl (Mac’da Command) ni bosing.</div>
                </div>
                <div class="col-md-4">
                    <label for="post-image" class="form-label">Asosiy rasm {{ $editing ? '(almashtirish ixtiyoriy)' : '*' }}</label>
                    <input id="post-image" type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" @if(! $editing) required @endif>
                    @error('image')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <img id="post-image-preview"
                         src="{{ $post->image ? $post->imageUrl : '' }}"
                         alt="Asosiy rasm"
                         class="img-thumbnail mt-2 {{ $post->image ? '' : 'd-none' }}"
                         style="height:90px;width:110px;object-fit:cover">
                    @if($post->image)
                        <div class="form-check mt-2">
                            <input type="checkbox" name="remove_image" value="1" id="remove-image" class="form-check-input" @checked(old('remove_image'))>
                            <label for="remove-image" class="form-check-label">Hozirgi rasmni o‘chirish</label>
                        </div>
                    @endif
                    <div class="form-text">Yangi rasm tanlansa, hozirgi rasm almashtiriladi.</div>
                </div>
                <div class="col-md-4">
                    <label for="post-image-hover" class="form-label">Hover rasmi</label>
                    <input id="post-image-hover" type="file" name="image_hover" class="form-control @error('image_hover') is-invalid @enderror" accept="image/*">
                    @error('image_hover')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <img id="post-image-hover-preview"
                         src="{{ $post->image_hover ? $post->hoverImageUrl : '' }}"
                         alt="Hover rasmi"
                         class="img-thumbnail mt-2 {{ $post->image_hover ? '' : 'd-none' }}"
                         style="height:90px;width:110px;object-fit:cover">
                    @if($post->image_hover)
                        <div class="form-check mt-2">
                            <input type="checkbox" name="remove_image_hover" value="1" id="remove-image-hover" class="form-check-input" @checked(old('remove_image_hover'))>
                            <label for="remove-image-hover" class="form-check-label">Hozirgi rasmni o‘chirish</label>
                        </div>
                    @endif
                    <div class="form-text">Yangi rasm tanlansa, hozirgi rasm almashtiriladi.</div>
                </div>
                <div class="col-md-4">
                    <label for="post-gallery" class="form-label">Galereya rasmlari</label>
                    <input id="post-gallery" type="file" name="gallery_images[]" class="form-control @error('gallery_images') is-invalid @enderror" accept="image/*" multiple>
                    @error('gallery_images')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @error('gallery_images.*')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @if($editing && $post->gallery_images)
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($post->gallery_images as $image)
                                <div class="border rounded p-1 text-center">
                                    <img src="{{ asset('storage/'.$image) }}" alt="Galereya rasmi" class="img-thumbnail" style="height:70px;width:90px;object-fit:cover">
                                    <div class="form-check mt-1">
                                        <input type="checkbox" name="remove_gallery[]" value="{{ $image }}" id="remove-gallery-{{ $loop->index }}" class="form-check-input" @checked(in_array($image, (array) old('remove_gallery', [])))>
                                        <label for="remove-gallery-{{ $loop->index }}" class="form-check-label small">O‘chirish</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    <div id="post-gallery-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                    <div class="form-text">Jami 4 tadan ko‘p bo‘lmasligi kerak. Yangi rasmlar mavjud galereyaga qo‘shiladi; eskilarini belgilab o‘chirish mumkin.</div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary">{{ $editing ? 'Saqlash' : 'Post yaratish' }}</button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">Bekor qilish</a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function bindPreview(inputId, previewId) {
        var input = document.getElementById(inputId);
        var preview = document.getElementById(previewId);

        if (!input || !preview) {
            return;
        }

        input.addEventListener('change', function () {
            var file = input.files && input.files[0];

            if (!file) {
                return;
            }

            preview.src = URL.createObjectURL(file);
            preview.classList.remove('d-none');
        });
    }

    bindPreview('post-image', 'post-image-preview');
    bindPreview('post-image-hover', 'post-image-hover-preview');

    var galleryInput = document.getElementById('post-gallery');
    var galleryPreview = document.getElementById('post-gallery-preview');

    if (galleryInput && galleryPreview) {
        var maxGalleryFiles = 4;

        galleryInput.addEventListener('change', function () {
            if (galleryInput.files.length > maxGalleryFiles) {
                var dataTransfer = new DataTransfer();

                Array.prototype.slice.call(galleryInput.files, 0, maxGalleryFiles).forEach(function (file) {
                    dataTransfer.items.add(file);
                });

                galleryInput.files = dataTransfer.files;
                alert('Galereyaga ko‘pi bilan 4 ta rasm yuklash mumkin.');
            }

            galleryPreview.innerHTML = '';

            Array.prototype.forEach.call(galleryInput.files, function (file) {
                var image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                image.alt = 'Yangi galereya rasmi';
                image.className = 'img-thumbnail';
                image.style.height = '70px';
                image.style.width = '90px';
                image.style.objectFit = 'cover';
                galleryPreview.appendChild(image);
            });
        });
    }
});
</script>
@endsection
