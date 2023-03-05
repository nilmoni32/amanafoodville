<ul class="app-menu">
    <li>
        <a class="app-menu__item bg-white text-primary {{ Route::currentRouteName() == 'admin.buffet.menu.edit' ? 'active' : '' }}"
            href="{{ route('admin.buffet.menu.edit', $buffet->id) }}">
            <span class="app-menu__label">General</span>
        </a>
    </li>
    <li>
        <a class="app-menu__item bg-white text-primary {{ Route::currentRouteName() == 'admin.buffet.menu.createOrder' ? 'active' : '' }}"
            href="{{ route('admin.buffet.menu.createOrder', $buffet->id) }}">
            <span class="app-menu__label">Order Place</span>
        </a>
    </li>
    <li>
        <a class="app-menu__item bg-white text-primary {{ Route::currentRouteName() == 'admin.buffet.sales.ordercheckout' ? 'active' : '' }}"
            href="{{ route('admin.buffet.sales.ordercheckout', $buffet->id) }}">
            <span class="app-menu__label">Checkout & Payment</span>
        </a>
    </li>
    
    @php if(Route::currentRouteName() == 'admin.buffet.recipe.index' ||
    Route::currentRouteName() == 'admin.buffet.recipe.create'){
    $temp = 1;
    }else{
    $temp = 0;
    }
    @endphp
    <li class="{{ $temp ? 'treeview is-expanded' : 'treeview' }}">
        <a class="app-menu__item bg-white text-primary" href="#" data-toggle="treeview">
            <span class="app-menu__label">Buffet Foods List</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a class="treeview-item bg-white text-primary {{ Route::currentRouteName() == 'admin.buffet.recipe.index' ? 'current' : '' }}"
                    href="{{ route('admin.buffet.recipe.index', $buffet->id)}}">List of all Foods</a>
            </li>
            <li>
                <a class="treeview-item bg-white text-primary {{ Route::currentRouteName() == 'admin.buffet.recipe.create' ? 'current' : '' }}"
                    href="{{ route('admin.buffet.recipe.create', $buffet->id)}}">Add Food</a>
            </li>            
        </ul>
    </li>
</ul>