@extends('layout-client.main')
@section('title', 'Color ' . $nameColor)

@section('content')

    <x-SizeBar />
    <x-Alert />
    @if ($products->count() > 0)
        Showing {{ $products->count() }} of {{ $products->total() }} results
    @endif

    <x-NameGenre>
        <x-slot name="nameGenre">
        </x-slot>
        {{ $nameColor }}
    </x-NameGenre>
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
            <span>Danh mục chưa có sản phẩn nào</span>
        @endforelse


    </div>
@endsection
@section('paginate')
    <div>
        {{ $products->links() }}
    </div>
@endsection
