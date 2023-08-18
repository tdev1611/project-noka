@extends('admin.layout')

@section('content')
    <script src="https://cdn.tiny.cloud/1/ycev3jqs96174pjltcois4npv3ucaz0uolrs5l7ra90v05qe/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#detail',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount image code',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | code | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            image_title: true,
            automatic_uploads: true,
            file_picker_types: 'image',
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function() {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.onload = function() {

                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            },
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'

        });
    </script>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Edit Product</h5>
                <h5 class="card-title fw-semibold mb-4 text-end"><a href="{{ route('admin.products.index') }}">Back</a>
                </h5>
                @if (session('error'))
                    <div class="alert alert-danger w-50" id="notification">
                        {{ session('error') }}
                    </div>
                @endif
                @if (session('success'))
                    <div class="alert alert-success w-50" id="notification">
                        {!! session('success') !!}
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.products.update', $product->id) }}" method="post"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Tên sản phẩm </label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', $product->name) }}">
                                    @error('name')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control" id="slug" name="slug"
                                        value="{{ old('slug', $product->slug) }}">
                                    @error('slug')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="name" class="form-label">Ảnh</label>
                                    <input type="file" class="form-control" id="image" name="image"
                                        value="{{ old('image') }}">
                                    @error('image')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                    <div>
                                        <img src="{{ url($product->image) }}" alt="" style="height: 30%"
                                            width="30%" class="m-1">
                                    </div>

                                </div>
                                {{-- price --}}
                                <div class="mb-3 col-md-6">
                                    <label for="price" class="form-label">Giá</label>
                                    <input type="number" class="form-control" id="price" name="price"
                                        value="{{ old('price', $product->price) }}">
                                    @error('price')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- desc  --}}
                                <div class="mb-3 col-md-6">
                                    <label for="desc" class="form-label">Mô tả sản phẩm</label>
                                    <textarea name="desc" id="desc" cols="30" rows="10" class="form-control">{{ old('desc', $product->desc) }}</textarea>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="list_image" class="form-label">Các Ảnh khác</label>
                                    <input type="file" class="form-control" id="list_image" name="list_image[]"
                                        value="{{ old('list_image') }}" style="" multiple>
                                    @error('list_image')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                    <div>
                                        @php
                                            $list_images = json_decode($product->list_image, true);
                                        @endphp
                                        @foreach ($list_images as $item)
                                            <img src="{{ url(htmlspecialchars($item)) }}" style="width:25%" class="m-1">
                                        @endforeach
                                    </div>
                                </div>
                                {{-- colors --}}
                                <div class="mb-3 col-md-6">
                                    <label for="color" class="form-label">Màu sản phẩm</label>
                                    <select name="colors[]" class="form-control" id="colors" multiple>
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->id }}"
                                                {{ old('colors') == $color->id ? 'selected' : '' }}
                                                @if (in_array($color->id, $selectColors)) selected @endif>
                                                {{ $color->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('colors')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- size --}}
                                <div class="mb-3 col-md-6">
                                    <label for="size" class="form-label">Sizes sản phẩm</label>
                                    <select name="sizes[]" class="form-control" id="sizes" multiple>

                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->id }}"
                                                {{ old('sizes') == $size->id ? 'selected' : '' }}
                                                @if (in_array($size->id, $selectSizes)) selected @endif>
                                                {{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('sizes')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                {{-- detail --}}
                                <div class="mb-3 col-md-12">
                                    <label for="detail" class="form-label">Chi tiết sản phẩm</label>
                                    <textarea name="detail" id="detail" cols="40" rows="20" class="form-control"> {{ old('detail', $product->detail) }} </textarea>
                                    @error('detail')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- category --}}
                                <div class="mb-3 col-md-12">
                                    <label for="category_id" class="form-label">Danh mục sản phẩm</label>
                                    <select name="category_id" class="form-control" id="category_id">
                                        @foreach ($categories as $key => $category)
                                            <option value="{{ $category->id }}"
                                                {{ old('category_id') == $category->id ? 'selected' : '' }}
                                                {{ $category->id == $product->category_id ? 'selected' : '' }}>
                                                {{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                </div>
                                {{-- status --}}
                                <div class="mb-3 col-md-12">
                                    <label for="status" class="form-label">Trạng thái</label>
                                    <select name="status" class="form-control" id="status">
                                        <option value="1" @if ($product->status == 1) selected @endif
                                            {{ old('status') == 2 ? 'selected' : '' }}>
                                            Hiển thị
                                        </option>
                                        <option value="2" @if ($product->status == 2) selected @endif
                                            {{ old('status') == 2 ? 'selected' : '' }}> Ẩn </option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger"> {{ $message }}</small>
                                    @enderror
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary">Sửa sản phẩm</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
