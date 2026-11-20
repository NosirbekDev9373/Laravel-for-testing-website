<x-layouts.main>

    <x-slot:title>
        {{ __("Postni o'zgartirish") }} #{{ $post->id }}
    </x-slot:title>

    <x-page-header>
        {{ __("Postni o'zgartirish") }} #{{ $post->id }}
    </x-page-header>

    <div class="container pt-3 pb-3">
        <div class="col-lg-6 col-12">
            <form class="custom-form consulting-form bg-white shadow-lg mb-5 mb-lg-0"
                  action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="consulting-form-header d-flex align-items-center">
                    <h3 class="mb-4">{{ __("Postni o'zgartirish") }}</h3>
                </div>

                <div class="consulting-form-body">
                    <div class="row">

                        <div class="col-12 mb-3">
                            <input type="text" name="title" class="form-control"
                                   value="{{ old('title', $post->title) }}" placeholder="{{ __('Sarlavha') }}"
                                   maxlength="255" required/>
                            @error('title')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <select name="category_id" class="form-control" required>
                                <option value="">{{ __('-- Kategoriya tanlang --') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <textarea name="short_content" rows="2" class="form-control"
                                      placeholder="{{ __('Qisqacha mazmuni') }}" maxlength="1000"
                                      required>{{ old('short_content', $post->short_content) }}</textarea>
                            @error('short_content')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 mb-3">
                            <textarea name="content" rows="4" class="form-control"
                                      placeholder="{{ __('Maqola') }}" required>{{ old('content', $post->content) }}</textarea>
                            @error('content')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Asosiy rasm -->
                        <div class="col-12 mb-3">
                            <label>{{ __('Asosiy rasm') }}</label>
                            <input type="file" name="image" class="form-control" accept="image/*"/>
                            @if($post->image)
                            <div class="form-text">{{ __('Hozirgi rasm:') }} {{ $post->image }}</div>
                            @endif
                            @error('image')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Hover rasm -->
                        <div class="col-12 mb-3">
                            <label>{{ __('Hover rasm (sichqon ustiga kelganda)') }}</label>
                            <input type="file" name="image_hover" class="form-control" accept="image/*"/>
                            @if($post->image_hover)
                            <div class="form-text">{{ __('Hozirgi rasm:') }} {{ $post->image_hover }}</div>
                            @endif
                            @error('image_hover')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-6 col-12 mb-3">
                            <input type="number" name="price" class="form-control"
                                   placeholder="{{ __('Narxi (so‘m)') }}" value="{{ old('price', $post->price) }}"
                                   min="0" step="0.01" required/>
                            @error('price')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-lg-6 col-md-6 col-12 mb-3">
                            <input type="number" name="duration" class="form-control"
                                   placeholder="{{ __('Ish soati') }}" value="{{ old('duration', $post->duration) }}"
                                   min="0" step="1" required/>
                            @error('duration')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Galereya rasmlari -->
                        <div class="col-12 mb-3">
                            <label>{{ __('Galereya rasmlari (bir nechta tanlash mumkin)') }}</label>
                            <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple data-max-files="4"/>
                            @if($post->gallery_images)
                            <div class="form-text">
                                {{ count($post->gallery_images) }} {{ __('ta mavjud rasm.') }}
                                {{ __('Yangi galereya yuklansa, eskilari almashtiriladi.') }}
                            </div>
                            @endif
                            @error('gallery_images')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            @error('gallery_images.*')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                            <div class="form-text">{{ __('Ko‘pi bilan 4 ta rasm yuklash mumkin. Yangi rasmlar mavjud galereyani almashtiradi.') }}</div>
                        </div>

                        <!-- Teglar -->
                        <div class="col-12 mb-3">
                            <label>{{ __('Teglar (bir nechta tanlash mumkin)') }}</label>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach($tags as $tag)
                                    <div class="form-check form-check-inline">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                               class="form-check-input" id="tag_{{ $tag->id }}"
                                               @checked(in_array($tag->id, old('tags', $post->tags->pluck('id')->all()))) />
                                        <label class="form-check-label" for="tag_{{ $tag->id }}">
                                            {{ $tag->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('tags')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                            @error('tags.*')
                            <div class="alert alert-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <div class="col-lg-6 col-md-10 col-8 mx-auto">
                        <button type="submit" class="form-control">{{ __('Saqlash') }}</button>
                    </div>
                    <div class="col-lg-6 col-md-10 col-8 mx-auto">
                        <a href="{{ route('posts.show', $post) }}" class="form-control">{{ __('Bekor qilish') }}</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

</x-layouts.main>
