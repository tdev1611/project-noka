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

        .btn-resote {
            display: inline-block;
            background: #149a25;
            padding: 3px;
            color: white;
        }
    </style>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">List Products ({{ $countProducts }})</h5>

                <form action="">
                    <div class="d-flex justify-content-end">
                        <input type="search" class="form-control rounded w-50" placeholder="Search" aria-label="Search"
                            aria-describedby="search-addon" value="{{ request()->input('search') }}" name="search" />
                        <button type="submit" class="btn btn-outline-primary">search</button>
                    </div>
                </form>

                <div>

                    {{-- <a class="dropdown-item mt-2 text-end btn btn-primary" href="{{ route('admin.products.create') }}">Add
                        Product</a> --}}

                    <a href="{{ route('admin.products.index') }}" class="text-muted d-block my-3">
                        Active
                        <span>({{ $countProducts }})</span>
                    </a>
                    @if ($trashed > 0)
                        <a href="{{ request()->fullUrlwithQuery(['status' => 'disabled']) }}"
                            class="text-muted d-block my-3">
                            Disabled <i class="fas fa-trash"></i>
                            <span>({{ $trashed }})</span></a>
                    @endif

                </div>

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
                    <form action="{{ route('admin.products.action') }}" method="post">
                        @csrf
                        <div class="input-group w-25">
                            <select class="form-control mr-1" name="action" id="myselect">
                                <option value="">Chọn</option>
                                @if (request()->status == 'active')
                                    <option value="delete">Xóa tạm thời </option>
                                @elseif (request()->status == 'disabled')
                                    <option value="restore">Khôi phục</option>
                                    <option value="forceDelelte">Xóa vĩnh viễn</option>
                                @elseif (request()->status == 'disable')
                                @else
                                    <option value="delete">Xóa tạm thời </option>
                                @endif
                            </select>
                            <input type="submit" name="btn-action" value="Áp dụng" class="btn btn-primary">
                        </div>
                        <div class="card-body ">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">
                                                <input type="checkbox" name="check_all" id="">
                                            </th>
                                            <th scope="col">#</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Colors</th>
                                            <th scope="col">Sizes</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Created_at</th>
                                            <th scope="col">Updated_at</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider ">
                                        @forelse ($products as $product)
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" name="list_check[]" value="{{ $product->id }}">
                                                </th>
                                                <th scope="row"> {{ $rank++ }}</th>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name }}</td>
                                                <td>{{ number_format($product->price, 0, '.', ',') }} đ</td>
                                                <td>
                                                    <img style="width: 100%" src="{{ url($product->image) }}"
                                                        alt="{{ $product->name }}" title="{{ $product->name }}">
                                                </td>
                                                {{-- corlos --}}
                                                <td>
                                                    @foreach ($product->colors as $color)
                                                        <span>{{ $color->name }},</span>
                                                    @endforeach
                                                </td>
                                                {{-- sizies --}}
                                                <td>
                                                    @foreach ($product->sizes as $size)
                                                        <span>{{ $size->name }},</span>
                                                    @endforeach
                                                </td>
                                                <td>{{ $product->desc }}</td>
                                                <td>
                                                    @if ($product->status == 1)
                                                        <span class="badge bg-primary">Hiển thị</span>
                                                    @else
                                                        <span class="badge bg-warning">Ẩn</span>
                                                    @endif
                                                </td>
                                                <td>{{ date_format($product->created_at, 'Y/m/d ') }}</td>
                                                <td>{{ $product->updated_at ? date_format($product->updated_at, 'Y/m/d H:i:s') : 'Product update yet!' }}
                                                </td>

                                                @if (request()->status == 'disabled')
                                                    <td class="d-flex">
                                                        <a class="btn btn-resote  me-2"
                                                            href="{{ route('admin.products.restore', $product->id) }}">
                                                            <i class="fas fa-trash-restore"></i>
                                                        </a>
                                                        {{-- delteforce --}}
                                                        <a type="button" class="btn btn-delete" data-bs-toggle="modal"
                                                            data-bs-target="#ModalDelteForce-{{ $product->id }}">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    </td>
                                                @else
                                                    <td class="d-flex">
                                                        <a class="btn btn-edit  me-2"
                                                            href="{{ route('admin.products.edit', $product->id) }}">Edit</a>
                                                        <a type="button" class="btn btn-delete" data-bs-toggle="modal"
                                                            data-bs-target="#delete-{{ $product->id }}">
                                                            Delete
                                                        </a>
                                                    </td>
                                                @endif

                                            </tr>
                                            <!-- Modal -->
                                            {{-- delteforce --}}
                                            @if ($trashed > 0)
                                                <div class="modal fade" id="ModalDelteForce-{{ $product->id }}"
                                                    tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel"> Delete
                                                                    force
                                                                    product <b>{{ $product->name }}</b>
                                                                </h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to <b>delete force</b> this Product ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">No</button>
                                                                <a href="{{ route('admin.products.deleteForce', $product->id) }}"
                                                                    class="btn btn-danger">Yes
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            {{-- end deleteforce --}}

                                            @if (!(request()->status == 'disabled'))
                                                <div class="modal fade" id="delete-{{ $product->id }}" tabindex="-1"
                                                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                    Delete
                                                                    product <b>{{ $product->name }}</b>
                                                                </h1>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Are you sure you want to delete this Product ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">No</button>

                                                                <a href="{{ route('admin.products.delete', $product->id) }}"
                                                                    class="btn btn-danger">Yes
                                                                </a>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                            <!-- End Modal -->
                                        @empty
                                            <td colspan="8">
                                                Chưa có sản phẩm nào
                                            </td>
                                        @endforelse

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>


    <!-- Modal delete-->
    {{-- <div class="modal fade" id="exampleModalDelete" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete Products ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <a href="{{ route('admin.products.delete', $product->id) }}" class="btn btn-danger">Yes</a>
                </div>
            </div>
        </div>
    </div> --}}
    {{--  --}}
    <!-- Modal deleteForce-->
    {{-- <div class="modal fade" id="exampleModalForceDelete" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"><b>Force delete</b> products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to Force delete Products ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger">Yes</button>
                </div>
            </div>
        </div>
    </div> --}}
    {{--  --}}
    <script>
        $(document).ready(function() {
            $('input[name="check_all"]').click(function() {

                let status = $(this).prop('checked');
                $('tr th input[type="checkbox"]').prop("checked", status);
            });

            $('input[name="list_check[]"]').click(function() {
                var id = $(this).val();
            });

            // $('#myselect').change(function() {
            //     let opval = $(this).val();
            //     if (opval == "delete") {
            //         $('#delete-}').modal("show");
            //     } else if (opval == "forceDelelte") {
            //         $('#exampleModalForceDelete').modal("show");
            //     }
            // });

        })
    </script>
@endsection
