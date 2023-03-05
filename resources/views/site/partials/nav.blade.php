<!-- Main Menu Start  -->
<div id="menu">
    <nav class="navbar navbar-expand-md">
        <div class="navbar-header">
            <span class="menutext d-block d-md-none"><a href="{{ url('/')}}"><img class="img-fluid"
                        src="{{ asset('frontend/images/Amana-FoodVille-logo-mobile.png')}}" alt="Funville" style="max-height:25px;"></a>
                @if(App\Models\Cart::totalItems())
                <a href="{{ route('cart.index') }}" class="btn-sm float-right text-white cart-icon"
                    style="margin:0 auto"><i class="icofont icofont-cart-alt h5"></i>(<span
                        id="totalItems_mob">{{ App\Models\Cart::totalCarts()->count() }}</span>)</a>
                @else
                <a href="{{ route('cart.index') }}" class="btn-sm float-right text-white cart-icon disabledbutton"
                    style="margin:0 auto"><i class="icofont icofont-cart-alt h5"></i>(<span
                        id="totalItems_mob">{{ 0 }}</span>)</a>
                @endif
            </span>

            <button data-target=".navbar-ex1-collapse" data-toggle="collapse" class="btn btn-navbar navbar-toggler"
                type="button"><i class="icofont icofont-navigation-menu"></i></button>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse padd0">
            <ul class="nav navbar-nav ml-auto">
                <li class="nav-item"><a href="{{ route('index') }}"
                        class="{{ Route::currentRouteName() == 'index' ? 'active' : '' }}">Home</a></li>
                <li class="nav-item"><a href="{{ route('about') }}"
                        class="{{ Route::currentRouteName() == 'about' ? 'active' : '' }}">About us</a></li>
                <li class="nav-item"><a href="{{ route('products.index') }}"
                        class="{{ Route::currentRouteName() == 'products.index' ||  Route::currentRouteName() == 'categoryproduct.show' ? 'active' : '' }}">Food
                        Menu</a></li>
                <li class="nav-item"><a href="{{ route('reservation') }}"
                        class="{{ Route::currentRouteName() == 'reservation' ? 'active' : '' }}">Reservation</a></li>
                <li class="nav-item"><a href="{{ route('contact') }}"
                        class="{{ Route::currentRouteName() == 'contact' ? 'active' : '' }}">contact us</a></li>
            </ul>
        </div>
    </nav>
</div>
<!-- Main Menu End -->