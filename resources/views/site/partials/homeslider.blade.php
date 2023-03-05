<div class="dishes">
    <div class="container">
        <div class="row">
            <!-- Title Content Start -->
            <div class="col-sm-12 commontop text-center">
                @php $category = App\Models\Category::where('slug', $slug_value)->first();@endphp
                <h4>Our {{  $category->category_name }}

                </h4>
                <div class="divider style-1 center">
                    <span class="hr-simple left"></span>
                    <i class="icofont icofont-ui-press hr-icon"></i>
                    <span class="hr-simple right"></span>
                </div>
                <p>{{ __('We are providing good food in rich taste with cheap rate. Please have a look of our cuisine food menu. Now you can have an idea about our food menu and have ready to taste certain foods of your heart desires. We are waiting for you with our all special dishes.') }}
                </p>
            </div>
            <!-- Title Content End -->
            <div class="col-sm-12">
                @php
                // getting the products using many to many relatioship
                $products = $category->products->sortByDesc('name');
                // only showing 5 products in the home page
                $top_5 = 1;
                @endphp
                <div class="dish owl-carousel">
                    @foreach($products as $product)
                    @if( $top_5 == 5)
                    @break;
                    @endif
                    {{-- checking the foriegn key value exists in products attributes table --}}
                    @php $attributeCheck = in_array($product->id, $product->attributes->pluck('product_id')->toArray())
                    @endphp
                    {{-- here product featured is half, quater food items --}}
                    @if(!($attributeCheck) && $product->status && !($product->featured) && $product->images->first())
                    <div class="item">
                        <div class="box">
                            @foreach($product->images as $image)
                            <img src="{!! asset('storage/'.$image->full) !!}" alt="image" title="{{ $product->name }}"
                                class="img-responsive" />
                            @endforeach

                            <div class="caption">
                                <h4>{{ substr($product->name, 0, 24) }}</h4>
                                {{-- if product discount price is available then we set it --}}
                                @if($product->discount_price)
                                <p> <span style="font-size: 18px;
                                    font-weight: 600;
                                    color: #e9457a; position:relative; top:2px;">{{ config('settings.currency_symbol') }} {{ round($product->discount_price,0)}}
                                    </span><span>[</span>
                                    <span
                                    style="text-decoration: line-through">{{ config('settings.currency_symbol') }}-{{ round($product->price,0) }}</span>
                                {{-- calculating the discount percentage --}}
                                <span>
                                    -{{ round(($product->price - $product->discount_price)*100/$product->price, 0) }}% ]</span>
                                </p>                                
                                @else
                                <p>{{ config('settings.currency_symbol') }} {{ round($product->price,0) }}</p>
                                @endif
                                <span
                                    class="text-center px-1 py-2 d-block">{{ substr($product->description,0, 70)}}</span>
                            </div>
                            {{-- <div class="cart-overlay" onclick="addToCart({{ $product->id }}, 0)">
                            <h5>Add to Cart</h5>
                        </div> --}}
                    </div>
                    <div class="hoverbox pb-2">
                        @php
                        //Checking the product is added to cart or not
                        if(Auth::check()){ //Auth::check() to check if the user is logged in
                        // when logged user adds products to cart.
                        $cart = App\Models\Cart::where('user_id', Auth::id())
                        ->where('product_id', $product->id)
                        ->where('order_id', NULL)
                        ->where('has_attribute', 0)
                        ->first();
                        }else{
                        // when a guest adds product to cart.
                        $cookie_name = "user_id";
            		$user_id = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
            		$cart = App\Models\Cart::where('ip_address', $user_id)
                        ->where('product_id', $product->id)
                        ->where('has_attribute', 0)
                        ->first();
                        }
                        @endphp
                        <button class="btn btn-theme-alt btn-cart btn-block {{ $cart ? 'display-none' : '' }}"
                            id="homeCartProductBtn{{ $product->id }}" onclick="addToCart({{ $product->id }}, 0)">Add
                            to
                            Cart</button>
                        <p class="{{ $cart ? '' : 'display-none' }} cart-msg" id="homeMsg{{ $product->id }}">
                            Food is added to Cart</p>
                    </div>
                </div>
                @elseif($attributeCheck && $product->status && !($product->featured) )
                {{-- if product has attribute value then we display them all--}}
                @foreach($product->attributes as $attribute)
                <div class="item">
                    <div class="box">
                        @foreach($product->images as $image)
                        <img src="{!! asset('storage/'.$image->full) !!}" alt="image" title="{{ $product->name }}"
                            class="img-responsive" />
                        @endforeach

                        <div class="caption">
                            <h4>{{ $product->name }}-({{ $attribute->size }})</h4>
                            {{-- if product discount price is available then we set it --}}
                            @if($attribute->special_price)
                            <p>{{ config('settings.currency_symbol') }}-{{ round($attribute->special_price,0)}}
                            </p>
                            <span
                                style="text-decoration: line-through">{{ config('settings.currency_symbol') }}-{{ round($attribute->price,0) }}</span>
                            {{-- calculating the discount percenate --}}
                            <span>
                                -{{ round(($attribute->price - $attribute->special_price)*100/$attribute->price, 0) }}%</span>
                            @else
                            <p>{{ config('settings.currency_symbol') }}-{{ round($attribute->price,0) }}</p>
                            @endif
                            <span class="text-center px-1 py-1 d-block">{{ substr($product->description,0, 35)}}</span>

                        </div>
                        {{-- <div class="cart-overlay" onclick="addToCart({{ $product->id }}, {{ $attribute->id }})">
                        <h5>Add to Cart</h5>
                    </div> --}}
                </div>
                <div class="hoverbox pb-2">
                    @php
                    //Checking the product is added to cart or not
                    if(Auth::check()){ //Auth::check() to check if the user is logged in
                    // when logged user adds products to cart.
                    $cart = App\Models\Cart::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->where('product_attribute_id', $attribute->id)
                    ->where('order_id', NULL)
                    ->where('has_attribute', 1)
                    ->first();
                    }else{
                    // when a guest adds product to cart.
                    $cookie_name = "user_id";
            	    $user_id = isset($_COOKIE[$cookie_name]) ? $_COOKIE[$cookie_name] : '';
                    $cart = App\Models\Cart::where('ip_address', $user_id)
                    ->where('product_id', $product->id)
                    ->where('product_attribute_id', $attribute->id)
                    ->where('has_attribute', 1)
                    ->first();
                    }
                    @endphp
                    <button class="btn btn-theme-alt btn-cart btn-block {{ $cart ? 'display-none' : '' }}"
                        id="homeCartSubProductBtn{{ $attribute->id }}"
                        onclick="addToCart({{ $product->id }}, {{ $attribute->id }})">Add
                        to Cart</button>
                    <p class="{{ $cart ? '' : 'display-none' }} cart-msg" id="homeSubMsg{{ $attribute->id }}">
                        Food is added to Cart</p>

                </div>
            </div>
            @endforeach
            @endif

            @php
            //incrementing the counter to show only five product
            $top_5 += 1;
            @endphp
            @endforeach
        </div>
    </div>
</div>
<!--  view more button  -->
<div class="row pt-5">
    <div class="col-sm-12 col-xs-12">
        <div class="text-center pb-2">
            <a class="btn btn-theme-alt btn-wide" href='{{ route('products.index') }}'>view more <i
                    class="icofont icofont-curved-double-right"></i></a>
        </div>
    </div>
</div>
</div>
</div>
