<ul class="app-menu">
    <li>
        <a class="app-menu__item bg-white text-primary {{ Route::currentRouteName() == 'admin.recipe.edit' ? 'active' : '' }}"
            href="{{ route('admin.recipe.edit', $recipe->id) }}">
            <span class="app-menu__label">General</span>
        </a>
    </li>
    @php if(Route::currentRouteName() == 'admin.recipe.ingredient.index' ||
    Route::currentRouteName() == 'admin.recipe.ingredient.create'){
    $temp = 1;
    }else{
    $temp = 0;
    }
    @endphp
    <li class="{{ $temp ? 'treeview is-expanded' : 'treeview' }}">
        <a class="app-menu__item bg-white text-primary" href="#" data-toggle="treeview">
            <span class="app-menu__label">Recipe ingredients</span>
            <i class="treeview-indicator fa fa-angle-right"></i>
        </a>
        <ul class="treeview-menu">
            <li>
                <a class="treeview-item bg-white text-primary {{ Route::currentRouteName() == 'admin.recipe.ingredient.index' ? 'current' : '' }}"
                    href="{{ route('admin.recipe.ingredient.index', $recipe->id)}}">List of all Ingredients</a>
            </li>
            <li>
                <a class="treeview-item bg-white text-primary {{ Route::currentRouteName() == 'admin.recipe.ingredient.create' ? 'current' : '' }}"
                    href="{{ route('admin.recipe.ingredient.create', $recipe->id)}}">Add Ingredient</a>
            </li>
        </ul>
    </li>
</ul>