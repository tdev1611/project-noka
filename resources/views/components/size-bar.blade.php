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