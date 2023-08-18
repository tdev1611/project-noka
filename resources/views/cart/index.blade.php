@extends('layout-client.main')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <style>
        body {
            margin-top: 20px;
            background: #eee;
        }

        .ui-w-40 {
            width: 40px !important;
            height: auto;
        }
        .card {
            box-shadow: 0 1px 15px 1px rgba(52, 40, 104, .08);
        }
        .ui-product-color {
            display: inline-block;
            overflow: hidden;
            margin: .144em;
            width: .875rem;
            height: .875rem;
            border-radius: 10rem;
            -webkit-box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.15) inset;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.15) inset;
            vertical-align: middle;
        }
    </style>
    <div class="container px-3 my-5 clearfix">
        <!-- Shopping cart table -->
        <div class="card">
            <div class="card-header">
                <h2>Shopping Cart</h2>
            </div>
            <div class="card-body">
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
                <div class="table-responsive">
                    <table class="table table-bordered m-0">
                        <thead>
                            <tr>
                                <!-- Set columns width -->
                                <th class="text-center py-3 px-4" style="min-width: 400px;">Product Name &amp; Details</th>
                                <th class="text-right py-3 px-4" style="width: 100px;">Price</th>
                                <th class="text-center py-3 px-4" style="width: 120px;">Quantity</th>
                                <th class="text-right py-3 px-4" style="width: 100px;">Total</th>
                                <th class="text-center align-middle py-3 px-0" style="width: 40px;">
                                    <a href="#" class="shop-tooltip float-none text-light" title=""
                                        data-original-title="Clear cart"><i class="ino ion-md-trash"></i>
                                    </a>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cart as $item)
                                @php
                                    $options = json_decode($item->options, true);
                                @endphp
                                <tr>
                                    <td class="p-4">
                                        <div class="media align-items-center">
                                            <a
                                                href="{{ route('product.detail', !Auth::user() ? $item->options->slug : $options['slug']) }}">
                                                <img src="{{ url(!Auth::user() ? $item->options->image : $options['image']) }}"
                                                    class="d-block ui-w-40 ui-bordered mr-4" alt="">
                                            </a>
                                            <div class="media-body">
                                                <a href="{{ route('product.detail', !Auth::user() ? $item->options->slug : $options['slug']) }}"
                                                    class="d-block text-dark">{{ $item->name }}</a>
                                                <small>
                                                    <span class="text-muted">Color:</span>
                                                    <span class=""
                                                        style=""></span>{{ !Auth::user() ? $item->options->color : $options['color'] }}
                                                    &nbsp;
                                                    <span class="text-muted">Size: </span>
                                                    {{ !Auth::user() ? $item->options->size : $options['size'] }} &nbsp;
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-right font-weight-semibold align-middle p-4">${{ $item->price }}</td>
                                    <td class="align-middle p-4">
                                        <input type="number" class="form-control text-center qty"
                                            value="{{ $item->qty }}" name="qty" id="qty"
                                            data-id="{{ $item->rowId }}" min="1">
                                    </td>
                                    <td class="text-right font-weight-semibold align-middle p-4"
                                        id="subtotal-{{ $item->rowId }}">
                                        ${{ $item->subtotal }}
                                    </td>
                                    <td class="text-center align-middle px-0">
                                        <a href="{{ route('cart.remove', $item->rowId) }}"
                                            class="shop-tooltip close float-none text-danger" title=""
                                            data-original-title="Remove">×</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- / Shopping cart table -->
                <div class="d-flex flex-wrap justify-content-between align-items-center pb-4">
                    <div class="mt-4">
                        <label class="text-muted font-weight-normal">Promocode</label>
                    </div>
                    <div class="d-flex">

                        <div class="text-right mt-4">
                            <label class="text-muted font-weight-normal m-0">Total price</label>
                            <div class="text-large"><strong id="total_price">
                                    ${{ !Auth::user() ? Cart::total() : $total }}</strong></div>
                        </div>
                    </div>
                </div>
                <div class="float-right">
                    <a href="{{ route('home') }}" class="btn btn-lg btn-default md-btn-flat mt-2 mr-3">Back to shopping</a>
                    <button type="button" class="btn btn-lg btn-primary mt-2">Checkout</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        // update cart
        $(document).ready(function() {

            $('.qty').change(function() {
                let qty = $(this).val();
                let rowId = $(this).attr('data-id');
                $.ajax({
                    url: "{{ route('cart.updateAjax') }}",
                    type: "post",
                    dataType: 'json',
                    data: {
                        qty: qty,
                        rowId: rowId,
                        _token: '{{ csrf_token() }}'
                    },
                    success(response) {
                        console.log(response.message);
                        $('#subtotal-' + rowId).text(response.subtotal)
                        $('#total_price').text(response.total)
                        $('#count_cart').text(response.cartCount)
                    }


                })


            })

        })
    </script>
@endsection
