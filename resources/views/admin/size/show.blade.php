@extends('admin.layout')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Edit size</h5>
                <h5 class="card-title fw-semibold mb-4 text-end"><a href="{{ route('admin.sizes.index') }}">Back</a></h5>
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

                        <form action=" {{ route('admin.sizes.update', $size->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <div class="col-12">
                                        <label for="name" class="form-label">Tên màu </label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $size->name) }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    {{-- status --}}
                                    <div class="col-12">
                                        <label for="status" class="form-label">Trạng thái</label>
                                        <select name="status" class="form-control" id="status">
                                            <option value="{{ $size->status }}">
                                                {{ $size->status == 1 ? 'Hiển thị' : 'Ẩn' }}
                                            </option>
                                            <option value="1">
                                                Hiển thị
                                            </option>
                                            <option value="2">
                                                Ẩn
                                            </option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-primary my-3">Sửa Size</button>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
    </div>
@endsection
