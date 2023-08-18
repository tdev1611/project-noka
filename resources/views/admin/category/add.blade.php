@extends('admin.layout')

@section('content')
    <style>
        .btn-delete {
            display: inline-block;
            background: #e82e87;
            padding: 3px;
            color: white;
        }

        .btn-edit {
            display: inline-block;
            background: #068FFF;
            padding: 3px;
            color: white;
        }
    </style>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Add Category</h5>
                <div class="card">
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
                    <div class="card-body">

                        <form action="{{ route('admin.categories.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <div class="col-12">
                                        <label for="name" class="form-label">Tên danh sách </label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="slug" class="form-label">Slug</label>
                                        <input type="text" class="form-control" id="slug" name="slug"
                                            value="{{ old('slug') }}">
                                        @error('slug')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    {{-- status --}}
                                    <div class="col-12">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select name="status" class="form-control" id="">
                                            <option value="1"> Hiển thị </option>
                                            <option value="2"> Ẩn </option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary my-3">Thêm sản phẩm</button>
                                </div>
                        </form>
                        {{-- index --}}
                        <div class="mb-3 col-md-6 table-responsive">
                            <table class="table table-hover ">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Tên danh sách</th>
                                        <th scope="col">Slug</th>
                                        <th scope="col">Trạng thái</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $temp = 1;
                                    @endphp
                                    @forelse ($categories as $category)
                                        <tr>
                                            <th scope="row">{{ $temp++ }}</th>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->slug }}</td>
                                            <td>
                                                @if ($category->status == 1)
                                                    <span class="badge bg-primary">Hiển thị</span>
                                                @else
                                                    <span class="badge bg-warning">Ẩn</span>
                                                @endif
                                            </td>
                                            <td class="d-flex">
                                                <a class="btn btn-edit  me-2"
                                                    href="{{ route('admin.categories.edit', $category->id) }}">Sửa</a>
                                                <a type="button" class="btn btn-delete" data-bs-toggle="modal"
                                                    data-bs-target="#exampleModal{{ $category->id }}">
                                                    Xóa
                                                </a>
                                            </td>
                                        </tr>
                                        <!-- Modal -->
                                        <div class="modal fade" id="exampleModal{{ $category->id }}" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel"> Delete
                                                            <b>{{ $category->name }}</b>
                                                        </h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete this category ?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">No</button>

                                                        <form
                                                            action="{{ route('admin.categories.destroy', $category->id) }}"
                                                            method="post">
                                                            @method('DELETE')
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger">Yes
                                                            </button>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <td colspan="5">Không có danh mục nào được tạo</td>
                                    @endforelse



                                </tbody>
                            </table>

                        </div>
                    </div>

                    {{--  --}}



                    {{--  --}}

                </div>



            </div>
        </div>

    </div>
    </div>
    </div>
@endsection
