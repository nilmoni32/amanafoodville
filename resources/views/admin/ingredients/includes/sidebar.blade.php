<ul class="app-menu">
    <li>
        <a class="app-menu__item bg-white text-primary {{ Route::currentRouteName() == 'admin.ingredient.edit' ? 'active' : '' }}"
            href="{{ route('admin.ingredient.edit', $ingredient->id) }}">
            <span class="app-menu__label">General</span>
        </a>
    </li>
    @php if(Route::currentRouteName() == 'admin.ingredient.purchase.index' ||
    Route::currentRouteName() == 'admin.ingredient.purchase.create'){
    $temp = 1;
    }else{
    $temp = 0;
    }
    @endphp
    <li class="{{ $temp ? 'treeview is-expanded' : 'treeview' }}">
        <a class="app-menu__item bg-white text-primary" href="#" data-toggle="treeview">
            <span class="app-menu__label">Purchase Ingredient</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a class="treeview-item bg-white text-primary {{ Route::currentRouteName() == 'admin.ingredient.purchase.index' ? 'current' : '' }}"
                    href="{{ route('admin.ingredient.purchase.index', $ingredient->id)}}">Purchasing List</a>
            </li>
            <li>
                <a class="treeview-item bg-white text-primary {{ Route::currentRouteName() == 'admin.ingredient.purchase.create' ? 'current' : '' }}"
                    href="{{ route('admin.ingredient.purchase.create', $ingredient->id)}}">Add Purchase</a>
            </li>
        </ul>
    </li>
    @php if(Route::currentRouteName() == 'admin.ingredient.damage.index' ||
    Route::currentRouteName() == 'admin.ingredient.damage.create'){
    $temp1 = 1;
    }else{
    $temp1 = 0;
    }
    @endphp
    <li class="{{ $temp1 ? 'treeview is-expanded' : 'treeview' }}">
        <a class="app-menu__item bg-white text-primary" href="#" data-toggle="treeview">
            <span class="app-menu__label">Damage Ingredient</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a class="treeview-item bg-white text-primary {{ Route::currentRouteName() == 'admin.ingredient.damage.index' ? 'current' : '' }} "
                    href="{{ route('admin.ingredient.damage.index', $ingredient->id)}}">All
                    Damages List</a>
            </li>
            <li>
                <a class="treeview-item bg-white text-primary {{ Route::currentRouteName() == 'admin.ingredient.damage.create' ? 'current' : '' }}"
                    href="{{ route('admin.ingredient.damage.create', $ingredient->id)}}">Add Damage
                    Entry</a>
            </li>
        </ul>
    </li>
</ul>