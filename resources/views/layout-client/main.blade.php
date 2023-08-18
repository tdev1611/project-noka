<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title> @yield('title') </title>

    <link rel="stylesheet" href="{{ asset('client-theme/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
        integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.0/jquery.min.js"
        integrity="sha512-Pa4Jto+LuCGBHy2/POQEbTh0reuoiEXQWXGn8S7aRlhcwpVkO8+4uoZVSOqUjdCsE+77oygfu2Tl+7qGHGIWsw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="{{ asset('client-theme/css/form-search.css') }}">
    <link rel="stylesheet" href="{{ url('resources/css/app.css') }}">

</head>

<body>

    <!-- partial:index.partial.html -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
    <div class="container">
        <div id="wrapper">
            <div id="checkout">
                CHECKOUT
            </div>
            <div id="info">
                @if (Auth::check())
                    <p>
                        Welcome, <b> {{ Auth::user()->name }}</b>
                    </p>
                @elseif (Auth::guard('admin')->check())
                    <p>Welcome, Admin
                        <b> {{ Auth::guard('admin')->user()->name }}</b>
                    <p>
                    @else
                    <p>Welcome, Guest</p>
                @endif
            </div>
            <div class="s003">
                <form action="{{ route('searchClient') }}">
                    <div class="inner-form"
                        style="display: flex;justify-content: flex-end;     padding-right: 15px;
                    padding-top: 4px;">
                        <div class="input-field second-wrap">
                            <input id="search" name="search" type="text" placeholder="Search?"
                                style="padding: 15px;" value="{{ request()->input('search') }}" />
                        </div>
                        <div class="input-field third-wrap">
                            <button class="btn-search" type="submit" style="padding: 15px;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <script src="js/extention/choices.js"></script>
            <script>
                const choices = new Choices('[data-trigger]', {
                    searchEnabled: false,
                    itemSelectText: '',
                });
            </script>



            <div id="header">
                <ul>
                    <li><a href="{{ route('home') }}">Home</a></li>
                    {{-- <li><a href="">Products</a></li> --}}
                </ul>
                @php
                    if (Auth::user()) {
                        $count_qty = App\Models\Cart::where('user_id', Auth::user()->id)->sum('qty');
                    }
                @endphp
                <h3>
                    <a style="text-decoration: none" href="{{ route('cart.index') }}">CART (<span
                            id="count_cart">{{ Auth::user() ? $count_qty : Cart::count() }}</span>)
                    </a>
                </h3>
            </div>

            @yield('content')
        </div>
    </div>
    @yield('paginate')
    <footer class="credit">Author: shipra - Distributed By: <a title="Awesome web design code & scripts"
            href="https://www.codehim.com?source=demo-page" target="_blank">CodeHim</a></footer>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> --}}
    <!-- partial -->
    <script src="{{ asset('client-theme/js/script.js') }}"></script>
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#notification').fadeOut('slow');
            }, 3000);
        })
    </script>

</body>

</html>
