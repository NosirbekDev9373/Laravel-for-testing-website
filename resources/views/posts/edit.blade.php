<x-layouts.main>

    <x-slot:title>
        Postni o'zgartirish #{{ $post->id }}
    </x-slot:title>

    <x-page-header>
        Postni o'zgartirish #{{ $post->id }}
    </x-page-header>

    <div class="container pt-3 pb-3">
        <div class="col-lg-6 col-12">
            <form class="custom-form consulting-form bg-white shadow-lg mb-5 mb-lg-0"
                  action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <div class="consulting-form-header d-flex align-items-center">
                    <h3 class="mb-4">Postni o'zgartirish</h3>
                </div>

                <div class="consulting-form-body">
                    <div class="row">

                        <div class="col-12 mb-3">
                            <input type="text" name="title" class="form-control"
                                   value="{{ $post->title }}" placeholder="Sarlavha" required/>
                            @error('title')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                        <select name="category_id" class="form-control" required>
                            <option value="">-- Kategoriya tanlang --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        </div>

                        <div class="col-12 mb-3">
                            <textarea name="short_content" rows="2" class="form-control"
                                      placeholder="Qisqacha mazmuni">{{ $post->short_content }}</textarea>
                            @error('short_content')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                <textarea name="content" rows="4" class="form-control"
                          placeholder="Maqola">{{ $post->content }}</textarea>
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
                                   placeholder="Narxi" value="{{ $post->price }}"/>
                            @error('price')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-6 col-12 mb-3">
                            <input type="number" name="duration" class="form-control"
                                   placeholder="Ish soati" value="{{ $post->duration }}"/>
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

                    </div>


                    <div class="col-lg-6 col-md-10 col-8 mx-auto">
                        <button type="submit" class="form-control">Saqlash</button>
                    </div>
                    <div class="col-lg-6 col-md-10 col-8 mx-auto">
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}" class="form-control">Bekor qilish</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-layouts.main>
