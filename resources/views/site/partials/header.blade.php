<!--  Header Start  -->
<header>
    <!--Top Header Start -->
    <div class="top">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <ul class="list-inline float-left icon">
                        <li class="list-inline-item"><a href="#"><i
                                    class="icofont icofont-phone"></i>{{ config('settings.phone_no') }}</a></li>
                    </ul>
                    <ul class="list-inline float-right icon">
                        <li class="list-inline-item dropdown">
                            @guest<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="icofont icofont-ui-user"></i> My Account</a>
                            <ul class="dropdown-menu dropdown-menu-right drophover" aria-labelledby="dropdownMenuLink">
                                <li class="dropdown-item"><a href="{{ route('login') }}">Login</a></li>
                                <li class="dropdown-item"><a href="{{ route('register') }}">Register</a></li>
                            </ul>
                            @else
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <i class="icofont icofont-ui-user"></i>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right drophover" aria-labelledby="dropdownMenuLink">
                                <li class="dropdown-item"><a
                                        href="{{ route('user.dashboard') }}">{{ __('Your Dashboard') }}</a></li>
                                <li class="dropdown-item"><a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a></li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </ul>
                            @endguest

                        </li>
                        <!-- Header Social Start -->
                        <li class="list-inline-item">
                            <ul class="list-inline social">
                                <li class="list-inline-item"><a href="{{ config('settings.social_facbook') }}"
                                        target="_blank"><i class="icofont icofont-social-facebook"></i></a></li>
                                <li class="list-inline-item"><a href="{{ config('settings.social_twitter') }}"
                                        target="_blank"><i class="icofont icofont-social-twitter"></i></a></li>
                                <li class="list-inline-item"><a href="{{ config('settings.social_instagram') }}"
                                        target="_blank"><i class="icofont icofont-social-instagram"></i></a>
                                </li>
                                <li class="list-inline-item"><a href="{{ config('settings.social_youtube') }}"
                                        target="_blank"><i class="icofont icofont-social-youtube-play"></i></a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <!-- Header Social End -->
                </div>
            </div>
        </div>
    </div>
    <!--Top Header End -->
    <div class="sticky">
        <div class="container">
            <div class="row">
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <!-- Logo Start  -->
                    <div id="logo">
                        <a href="{{ url('/')}}">
                            <img id="logo_img" class="img-fluid"
                                src="{{ asset('storage/'.config('settings.site_logo')) }}" alt="logo"
                                title="logo" /></a>
                    </div>
                    <!-- Logo End  -->
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12 float-right">
                    @include('site.partials.nav')
                </div>
                <div class="col-md-3 col-sm-12 col-xs-12 float-right mt-3 menu-search">
                    <form action="{{ route('search') }}" class="form-horizontal search-icon" method='get'>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search Food" name="search">
                            <div class="input-group-append">
                                <button class="btn btn-theme" type="submit">
                                    <i class="icofont icofont-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
                @if(App\Models\Cart::totalItems())
                <div class="col-md-1 col-sm-4 col-xs-4 paddleft mt-4 shop-cart">
                    <ul class="list-inline float-right icon mt-2">
                        <li class="list-inline-item"><a href="{{ route('cart.index') }}" class="btn-sm"><i
                                    class="icofont icofont-cart-alt h4" style="position: relative;top:2px;"></i>(<span
                                    id="totalItems_desktop">{{ App\Models\Cart::totalCarts()->count() }}</span>)</a>
                        </li>
                    </ul>
                </div>
                @else
                <div class="col-md-1 col-sm-4 col-xs-4 paddleft mt-4 shop-cart disabledbutton">
                    <ul class="list-inline float-right icon mt-2">
                        <li class="list-inline-item"><a href="{{ route('cart.index') }}" class="btn-sm"><i
                                    class="icofont icofont-cart-alt h4" style="position: relative;top:2px;"></i>(<span
                                    id="totalItems_desktop">{{ 0 }}</span>)</a>
                        </li>
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</header>
<!-- Header End   -->