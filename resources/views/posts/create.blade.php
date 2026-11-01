<x-layouts.main>

    <x-slot:title>
        Yangi Post yaratish
    </x-slot:title>

    <x-page-header>
        Yangi Post yaratish
    </x-page-header>
    <div class="container pt-3 pb-3">
        <div class="col-lg-6 col-12">
            <form class="custom-form consulting-form bg-white shadow-lg mb-5 mb-lg-0"
                  action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="consulting-form-header d-flex align-items-center">
                <h3 class="mb-4">Yangi post yaratish</h3>
            </div>

            <div class="consulting-form-body">
                <div class="row">

                    <div class="col-12 mb-3">
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title') }}" placeholder="Sarlavha" required/>
                        @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Kategoriya tanlang --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"> {{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 mb-3">
                            <textarea name="short_content" rows="2" class="form-control"
                                      placeholder="Qisqacha mazmuni" required>{{ old('short_content') }}</textarea>
                        @error('short_content')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                <textarea name="content" rows="4" class="form-control"
                          placeholder="Maqola" required>{{ old('content') }}</textarea>
                        @error('content')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Asosiy rasm -->
                    <div class="col-12 mb-3">
                        <label>Asosiy rasm</label>
                        <input type="file" name="image" class="form-control"/>
                        @error('image')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hover rasm -->
                    <div class="col-12 mb-3">
                        <label>Hover rasm (sichqon ustiga kelganda)</label>
                        <input type="file" name="image_hover" class="form-control"/>
                        @error('image_hover')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-6 col-12 mb-3">
                        <input type="number" name="price" class="form-control"
                               placeholder="Narxi" value="{{ old('price') }}" required/>
                        @error('price')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-lg-6 col-md-6 col-12 mb-3">
                        <input type="number" name="duration" class="form-control"
                               placeholder="Ish soati" value="{{ old('duration') }}" required/>
                        @error('duration')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- Galereya rasmlari -->
                    <div class="col-12 mb-3">
                        <label>Galereya rasmlari (bir nechta tanlash mumkin)</label>
                        <input type="file" name="gallery_images[]" class="form-control" multiple/>
                        @error('gallery_images')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- Teglar -->
                    <div class="col-12 mb-3">
                        <label>Teglar (bir nechta tanlash mumkin)</label>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            @foreach($tags as $tag)
                                <div class="form-check form-check-inline">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                           class="form-check-input" id="tag_{{ $tag->id }}"
                                        {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}/>
                                    <label class="form-check-label" for="tag_{{ $tag->id }}">
                                        {{ $tag->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('tags')
                        <div class="alert alert-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                </div>


                <div class="col-lg-6 col-md-10 col-8 mx-auto">
                    <button type="submit" class="form-control">Saqlash</button>
                </div>
            </div>
            </form>
        </div>
    </div>


</x-layouts.main>
