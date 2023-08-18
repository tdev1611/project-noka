@extends('layout-client.main')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <style>
        .icon-hover:hover {
            border-color: #3b71ca !important;
            background-color: white !important;
            color: #3b71ca !important;
        }

        .icon-hover:hover i {
            color: #3b71ca !important;
        }

        .item-thumb {
            cursor: pointer;
        }

        #notificationAjax {
            display: none;
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 2.5rem 1.25rem;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 1.0625rem;
            min-width: 18.75rem;
            z-index: 9999;
            border-radius: 15px;
            opacity: 0.8;
        }
    </style>
    <!-- content -->
    <section class="py-5">
        <div class="container">
            <div id="notificationAjax">
                <span id="chi-notificationAjax">

                </span>
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
            <form action="{{ route('cart.buyNow', $product->id) }}" method="get">
                @csrf
                <div class="row gx-5">
                    <aside class="col-lg-6">
                        <div class="border rounded-4 mb-3 d-flex justify-content-center show-pic">
                            <a class="rounded-4" data-type="image" id="image-container">
                                <img style="max-width: 100%; max-height: 100vh; margin: auto;" class="rounded-4 fit"
                                    src="{{ url($product->image) }}" />
                            </a>
                        </div>
                        <div class="d-flex justify-content-center mb-3">
                            @foreach (json_decode($product->list_image, true) as $item)
                                <p class="border mx-1 rounded-2 item-thumb " data-type="image">
                                    <img width="60" height="60" class="rounded-2" src="{{ url($item) }}" />
                                </p>
                            @endforeach
                        </div>
                        <!-- thumbs-wrap.// -->
                        <!-- gallery-wrap .end// -->
                    </aside>
                    <main class="col-lg-6">

                        <div class="ps-lg-3">
                            <h4 class="title text-dark">
                                {{ $product->name }}
                            </h4>
                            <div class="d-flex flex-row my-3">

                                <span class="text-muted"><i class="fas fa-shopping-basket fa-sm mx-1"></i>154 orders</span>
                                <span class="text-success ms-2">In stock</span>
                            </div>

                            <div class="mb-3">
                                <span class="h5">${{ $product->price }}</span>
                                <span class="text-muted">/per box</span>
                            </div>
                            <p>
                                {{ $product->desc }}
                            </p>
                            <div class="row">
                                <dt class="col-3">Type:</dt>
                                <dd class="col-9"> {{ $product->desc }}</dd>
                                <div class="col-md-4 col-6">
                                    <label class="mb-2" for="sizes">Sizes</label>
                                    <select class="form-select border border-secondary  w-50" style="height: 35px;"
                                        name="sizes" id="sizes">
                                        @foreach ($sizes as $size)
                                            <option value="{{ $size->name }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <hr />

                            <div class="row mb-4">
                                <div class="col-md-4 col-6">
                                    <label class="mb-2" for="colors">Colors</label>
                                    <select class="form-select border border-secondary" style="height: 35px;" name="colors"
                                        id="colors">
                                        @foreach ($colors as $color)
                                            <option value="{{ $color->name }}">{{ $color->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- col.// -->
                                <div class="col-md-4 col-6 mb-3">
                                    <label class="mb-2 d-block">Quantity</label>
                                    <div class="input-group mb-3" style="width: 170px;">
                                        <button class="btn btn-white border border-secondary px-3" type="button"
                                            id="button-addon1" data-mdb-ripple-color="dark">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <input type="text" id="num_order" name="num_order"
                                            class="form-control text-center border border-secondary" value="1"
                                            aria-label="Example text with button addon" />
                                        <button class="btn btn-white border border-secondary px-3" type="button"
                                            id="button-addon2" data-mdb-ripple-color="dark">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            {{-- <button type="submit" class="btn btn-warning shadow-0"> Buy now </button> --}}
                            <button data-id="{{ $product->id }}" type="submit" id="add-cart"
                                class="btn btn-primary shadow-0"> <i class="me-1 fa fa-shopping-basket"></i>
                                Add to cart </button>

                        </div>

                    </main>
                </div>
            </form>
        </div>
    </section>
    <!-- content -->

    <script>
        $(document).ready(function() {
            $(".item-thumb").click(function() {
                let src_attr = $(this).find('img').attr('src')
                $('#image-container img').attr('src', src_attr)
            })

            // change val
            let currentVal = $('#num_order').val();
            $('#button-addon2').click(function() {
                currentVal++;
                $('#num_order').attr('value', currentVal);
            });
            $('#button-addon1').click(function() {
                if (currentVal > 1) {
                    currentVal--;
                    $('#num_order').attr('value', currentVal);
                }
            });
        })
    </script>

    <!-- Footer -->
    {{-- ajax --}}
    <script>
        $(document).ready(function() {

            $('#add-cart').click(function(e) {
                e.preventDefault();
                let id = $(this).attr('data-id');
                let qty = $('#num_order').val()
                let color = $('#colors').val()
                let size = $('#sizes').val()

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    type: 'post',
                    dataType: 'json',
                    data: {
                        id: id,
                        qty: qty,
                        color: color,
                        size: size,
                        _token: '{{ csrf_token() }}'
                    },
                    success(response) {
                        $('#count_cart').text(response.cartCount)
                        if (response.status == 'success') {
                            console.log(response.messages);
                            $('#chi-notificationAjax').text(response.messages);

                        } else if (response.status == 'false') {
                            console.log(response.messages);
                            $('#chi-notificationAjax').text(response.messages);
                        }
                        $('#notificationAjax').show()
                        setTimeout(function() {
                            $('#notificationAjax').fadeOut('slow');
                        }, 3000);
                    },
                    error(jqXHR, textStatus, errorThrown) {
                        console.error('AJAX Error:', textStatus, errorThrown);
                    }


                })

            })



        })
    </script>
@endsection
