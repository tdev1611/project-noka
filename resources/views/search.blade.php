@extends('layout-client.main')

@section('content')
    <style>
        .alert-danger {
            background: #c34747;
            padding: 9px;
            text-align: center;
            width: 35%;
            color: #fff;
        }

        .alert-success {
            background: #63c14e;
            padding: 9px;
            text-align: center;
            width: 35%;
            color: #fff;
        }
    </style>
    <div id="sidebar">

        <h3>CATEGORIES</h3>
        <div class="checklist categories">
            <ul>
                @foreach ($categories as $category)
                    <li><a href="{{ route('product.byCategory', $category->slug) }}"> {{ $category->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <h3>COLORS</h3>
        <div class="checklist colors">
            @php
                $temp = 0;
            @endphp
            @foreach ($colors as $color)
                @if ($temp % 2 == 0)
                    <ul>
                        <li><a href="{{ route('product.ByColor', $color->slug) }}"><span
                                    style="background:{{ $color->name }}"></span>{{ $color->name }}</a></li>
                        {{-- <li><a href=""><span style="background:#222"></span>Black</a></li> --}}
                    </ul>
                @else
                    <ul>
                        <li><a href="{{ route('product.ByColor', $color->slug) }}">{{ $color->name }}</a></li>
                    </ul>
                @endif
            @endforeach
        </div>
        <h3>SIZES</h3>
        <div class="checklist sizes">
            @php
                $temp = 0;
            @endphp
            @foreach ($sizes as $size)
                @if ($temp % 2 == 0)
                    <ul>
                        <li><a href="{{ route('product.BySize', $size->slug) }}">{{ $size->name }}</a></li>
                    </ul>
                @else
                    <ul>

                        <li><a href="{{ route('product.BySize', $size->slug) }}">{{ $size->name }}</a></li>
                    </ul>
                @endif
            @endforeach

        </div>


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
    <div id="grid-selector">
        <div id="grid-menu">
            View:
            <ul>
                <li class="largeGrid"><a href=""></a></li>
                <li class="smallGrid"><a class="active" href=""></a></li>
            </ul>
        </div>
        Showing 1–9 of 48 results
    </div>

    <div id="grid">

        @forelse ($products as $product)
            <div class="product">
                <div class="make3D">
                    <div class="product-front">
                        <div class="shadow"></div>
                        <img src="{{ url($product->image) }}" alt="{{ $product->name }}" />
                        <div class="image_overlay"></div>
                        <div class="add_to_cart">
                            <a href="{{ route('product.detail', $product->slug) }}"
                                style="text-decoration: none; color:white">View detail</a>
                        </div>
                        <div class="view_gallery">View gallery</div>
                        <div class="stats">
                            <div class="stats-container">
                                <span class="product_price">${{ $product->price }}</span>
                                <a style="text-decoration: none" href="{{ route('product.detail', $product->slug) }}">
                                    <span class="product_name">{{ $product->name }}</span></a>
                                <p>{{ $product->desc }}</p>

                                <div class="product-options">
                                    <strong>SIZES</strong>
                                    <span>
                                        @foreach ($product->sizes as $size)
                                            {{ $size->name }}
                                        @endforeach
                                    </span>
                                    <strong>COLORS</strong>
                                    <div class="colors">
                                        @foreach ($product->colors as $color)
                                            <div class="c-{{ strtolower($color->name) }}"><span></span></div>
                                        @endforeach
                                        {{-- <div class="c-purple"><span></span></div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-back">
                        <div class="shadow"></div>
                        <div class="carousel">
                            <ul class="carousel-container">
                                @foreach (json_decode($product->list_image, true) as $image)
                                    <li>
                                        <img src="{{ url($image) }}" alt="{{ $product->name }}"
                                            title="{{ $product->name }}" />
                                    </li>
                                @endforeach

                            </ul>
                            <div class="arrows-perspective">
                                <div class="carouselPrev">
                                    <div class="y"></div>
                                    <div class="x"></div>
                                </div>
                                <div class="carouselNext">
                                    <div class="y"></div>
                                    <div class="x"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flip-back">
                            <div class="cy"></div>
                            <div class="cx"></div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="shopee-search-empty-result-section"><img width="18%"
                    src="https://deo.shopeemobile.com/shopee/shopee-pcmall-live-sg/assets/a60759ad1dabe909c46a817ecbf71878.png"
                    class="shopee-search-empty-result-section__icon">
                <div class="shopee-search-empty-result-section__title">Không tìm thấy kết quả nào  của từ khóa  : <b>{{ $search }}</b></div>
                <div class="shopee-search-empty-result-section__hint">Hãy thử sử dụng các từ khóa chung chung hơn</div>
            </div>
        @endforelse

    </div>
@endsection
