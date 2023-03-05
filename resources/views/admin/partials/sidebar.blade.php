<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
  <div class="app-sidebar__user">
    <div class="pl-2">
      <p class="app-sidebar__user-name">{{ Auth::guard('admin')->user()->name  }}</p>
      @if(Auth::user()->roles()->first()->name == 'admin')
      <p class="app-sidebar__user-designation"> {{ __('[ Administrator ]') }} </p>
      @elseif(Auth::user()->hasRole('order-control') && Auth::user()->hasRole('stock-control'))
      <p class="app-sidebar__user-designation"> {{ __('[ Controller ]') }} </p>
      @elseif(Auth::user()->hasRole('order-control'))
      <p class="app-sidebar__user-designation"> {{ __('[ Order Controller ]') }} </p>
      @elseif(Auth::user()->hasRole('stock-control'))
      <p class="app-sidebar__user-designation"> {{ __('[ Inventory Controller ]') }} </p>
      @elseif(Auth::user()->hasRole('super-admin'))
      <p class="app-sidebar__user-designation"> {{ __('[ Super Admin ]') }} </p>
      @else
      <p class="app-sidebar__user-designation"> {{ __('[ Generic User ]') }} </p>
      @endif
    </div>
  </div>
  <ul class="app-menu">
    @can('funville-dashboard')
    <li>
      {{-- if current route name is dashboard we will set active class  --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.dashboard' ? 'active' : ''}}"
        href="{{route('admin.dashboard')}}">
        <i class="app-menu__icon fa fa-dashboard"></i>
        <span class="app-menu__label">Dashboard</span>
      </a>
    </li>
    @endcan

    @can('manage-reports')
    @php if(Route::currentRouteName() == 'admin.reports.ecom.profitloss' ||
    Route::currentRouteName() == 'admin.reports.ecom.getprofitloss' ||
    Route::currentRouteName() == 'admin.reports.ecom.cashregister' ||
    Route::currentRouteName() == 'admin.reports.ecom.getcashregister' ||
    Route::currentRouteName() == 'admin.reports.top20' ||
    Route::currentRouteName() == 'admin.reports.getTop20'||
    Route::currentRouteName() == 'admin.reports.single' ||
    Route::currentRouteName() == 'admin.reports.singleSale'){
    $temp = 1;
    }else{
    $temp = 0;
    }
    @endphp
    <li class="{{ $temp ? 'treeview is-expanded' : 'treeview' }}">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa fa-braille"></i><span class="app-menu__label">Ecommerce Reports</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        @can('all-admin-features')
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.ecom.profitloss' ||
                                     Route::currentRouteName() == 'admin.reports.ecom.getprofitloss' ? 'active' : '' }}"
            href="{{ route('admin.reports.ecom.profitloss') }}">
            <i class="icon fa fa-circle-o"></i>Profit and Loss</a>
        </li>
        @endcan
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.ecom.cashregister' ||
          Route::currentRouteName() == 'admin.reports.ecom.getcashregister' ? 'active' : '' }}"
            href="{{ route('admin.reports.ecom.cashregister') }}">
            <i class="icon fa fa-circle-o"></i>Cash Register Wise Sales</a>
        </li>
        {{-- <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.single' ||
           Route::currentRouteName() == 'admin.reports.singleSale' ? 'active' : '' }}"
            href="{{ route('admin.reports.single') }}">
            <i class="icon fa fa-circle-o"></i>Single Item Wise Sales</a>
        </li> --}}
      </ul>
    </li>
    @php if(Route::currentRouteName() == 'admin.reports.combined.profitLoss' ||
            Route::currentRouteName() == 'admin.reports.combined.getcombinedprofitLoss' ||
            Route::currentRouteName() == 'admin.reports.profitLoss' ||
            Route::currentRouteName() == 'admin.reports.getprofitloss' ||
            Route::currentRouteName() == 'admin.reports.cashRegister' ||
            Route::currentRouteName() == 'admin.reports.getCashRegister' ||
            Route::currentRouteName() == 'admin.reports.customerSales' ||
            Route::currentRouteName() == 'admin.reports.getCustomerSales' ||
            Route::currentRouteName() == 'admin.reports.complimentarySales' ||
            Route::currentRouteName() == 'admin.reports.getcomplimentarySales' ||
            Route::currentRouteName() == 'admin.reports.bonusPoint' ||
            Route::currentRouteName() == 'admin.reports.stock' ||
            Route::currentRouteName() == 'admin.reports.getstock' ||
            Route::currentRouteName() == 'admin.reports.digitalPayments' ||
            Route::currentRouteName() == 'admin.reports.getdigitalPayments' ||
            Route::currentRouteName() == 'admin.reports.ingredientPurchase' ||
            Route::currentRouteName() == 'admin.reports.getingredientPurchase' ||
            Route::currentRouteName() == 'admin.reports.refDiscount' ||
            Route::currentRouteName() == 'admin.reports.getrefDiscount' ||
            Route::currentRouteName() == 'admin.reports.due.salesTotal' ||
            Route::currentRouteName() == 'admin.reports.supplier.requisition'||
            Route::currentRouteName() == 'admin.reports.supplier.getrequisition'||
            Route::currentRouteName() == 'admin.reports.supplier.challan'||
            Route::currentRouteName() == 'admin.reports.supplier.getchallan' ||
            Route::currentRouteName() == 'admin.reports.supplier.return'||
            Route::currentRouteName() == 'admin.reports.supplier.getreturn' ||
            Route::currentRouteName() == 'admin.reports.product.disposal' ||
            Route::currentRouteName() == 'admin.reports.product.getdisposal'||
            Route::currentRouteName() == 'admin.reports.buffet.profitloss' ||
            Route::currentRouteName() == 'admin.reports.buffet.getprofitloss' ||
            Route::currentRouteName() == 'admin.reports.buffet.cashRegister' ||
            Route::currentRouteName() == 'admin.reports.buffet.getCashRegister' ||
            Route::currentRouteName() == 'admin.reports.combined.vat'||
            Route::currentRouteName() == 'admin.reports.combined.getvat'||
            Route::currentRouteName() == 'admin.reports.percentage.profitLoss'||
            Route::currentRouteName() == 'admin.reports.percentage.getprofitLoss' ||
            Route::currentRouteName() == 'admin.reports.percentage.vat'||
            Route::currentRouteName() == 'admin.reports.percentage.getvat' ||
            Route::currentRouteName() == 'admin.reports.percentage.cashRegister' ||
            Route::currentRouteName() == 'admin.reports.percentage.getCashRegister' ||
            Route::currentRouteName() == 'admin.reports.buffet.percentage.cashRegister' ||
            Route::currentRouteName() == 'admin.reports.buffet.percentage.getCashRegister'
            ){
    $flag = 1;
    }else{
    $flag = 0;
    }
    @endphp
    <li class="{{ $flag ? 'treeview is-expanded' : 'treeview' }}">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-registered"></i><span class="app-menu__label">MIS Reports</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.percentage.profitLoss' ||
          Route::currentRouteName() == 'admin.reports.percentage.getprofitLoss' ? 'active' : '' }}"
            href="{{ route('admin.reports.percentage.profitLoss') }}">
            <i class="icon fa fa-dot-circle-o"></i>Profit and Loss</a>
        </li>
        @if(config('settings.tax_percentage') || config('settings.tax_include_percentage'))
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.percentage.vat' ||
          Route::currentRouteName() == 'admin.reports.percentage.getvat' ? 'active' : '' }}"
            href="{{ route('admin.reports.percentage.vat') }}">
            <i class="icon fa fa-dot-circle-o"></i>Vat</a>
        </li>
        @endif
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.percentage.cashRegister' ||
          Route::currentRouteName() == 'admin.reports.percentage.getCashRegister' ? 'active' : '' }}"
            href="{{ route('admin.reports.percentage.cashRegister') }}">
            <i class="icon fa fa-dot-circle-o"></i>Cash Register Sales [KOT]</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.buffet.percentage.cashRegister' ||
          Route::currentRouteName() == 'admin.reports.buffet.percentage.getCashRegister' ? 'active' : '' }}"
            href="{{ route('admin.reports.buffet.percentage.cashRegister') }}">
            <i class="icon fa fa-dot-circle-o"></i>Cash Register Sales [buffet]</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.ingredientPurchase' ||
            Route::currentRouteName() == 'admin.reports.getingredientPurchase' ? 'active' : '' }}"
            href="{{ route('admin.reports.ingredientPurchase') }}">
            <i class="icon fa fa-dot-circle-o"></i>Ingredient Purchase Details</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.due.salesTotal' ||
            Route::currentRouteName() == 'admin.reports.due.getsalesTotal' ? 'active' : '' }}"
            href="{{ route('admin.reports.due.salesTotal') }}">
            <i class="icon fa fa-dot-circle-o"></i>Due Cash Register Sales</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.refDiscount' ||
            Route::currentRouteName() == 'admin.reports.getrefDiscount' ? 'active' : '' }}"
            href="{{ route('admin.reports.refDiscount') }}">
            <i class="icon fa fa-dot-circle-o"></i>Reference Discount Details</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.customerSales' ||
          Route::currentRouteName() == 'admin.reports.getCustomerSales' ? 'active' : '' }}"
            href="{{ route('admin.reports.customerSales') }}">
            <i class="icon fa fa-dot-circle-o"></i>Customer Wise Sales</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.bonusPoint' ? 'active' : '' }}"
            href="{{ route('admin.reports.bonusPoint') }}">
            <i class="icon fa fa-dot-circle-o"></i>Customer Bonus Point</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.complimentarySales' ||
            Route::currentRouteName() == 'admin.reports.getcomplimentarySales' ? 'active' : '' }}"
            href="{{ route('admin.reports.complimentarySales') }}">
            <i class="icon fa fa-dot-circle-o"></i>Complimentary Sales</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.stock' ||
          Route::currentRouteName() == 'admin.reports.getstock' ? 'active' : '' }}"
            href="{{ route('admin.reports.stock') }}">
            <i class="icon fa fa-dot-circle-o"></i>Stock Report</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.supplier.requisition' ||
          Route::currentRouteName() == 'admin.reports.supplier.getrequisition' ? 'active' : '' }}"
            href="{{ route('admin.reports.supplier.requisition') }}">
            <i class="icon fa fa-dot-circle-o"></i>Supplier Requisition</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.supplier.challan' ||
          Route::currentRouteName() == 'admin.reports.supplier.getchallan' ? 'active' : '' }}"
            href="{{ route('admin.reports.supplier.challan') }}">
            <i class="icon fa fa-dot-circle-o"></i>Supplier Challan</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.supplier.return' ||
          Route::currentRouteName() == 'admin.reports.supplier.getreturn' ? 'active' : '' }}"
            href="{{ route('admin.reports.supplier.return') }}">
            <i class="icon fa fa-dot-circle-o"></i>Supplier Return</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.product.disposal' ||
          Route::currentRouteName() == 'admin.reports.product.getdisposal' ? 'active' : '' }}"
            href="{{ route('admin.reports.product.disposal') }}">
            <i class="icon fa fa-dot-circle-o"></i>Product Disposal</a>
        </li>
        @can('all-admin-features')
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.combined.profitLoss' ||
          Route::currentRouteName() == 'admin.reports.combined.getcombinedprofitLoss' ? 'active' : '' }}"
            href="{{ route('admin.reports.combined.profitLoss') }}">
            <i class="icon fa fa-dot-circle-o"></i>Combined Profit and Loss</a>
        </li>
        @if(config('settings.tax_percentage') || config('settings.tax_include_percentage')  )
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.combined.vat' ||
          Route::currentRouteName() == 'admin.reports.combined.getvat' ? 'active' : '' }}"
            href="{{ route('admin.reports.combined.vat') }}">
            <i class="icon fa fa-dot-circle-o"></i>Combined Vat</a>
        </li>
        @endif
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.profitLoss' ||
          Route::currentRouteName() == 'admin.reports.getprofitloss' ? 'active' : '' }}"
            href="{{ route('admin.reports.profitLoss') }}">
            <i class="icon fa fa-dot-circle-o"></i>Profit and Loss (KOT)</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.buffet.profitloss' ||
          Route::currentRouteName() == 'admin.reports.buffet.getprofitloss' ? 'active' : '' }}"
            href="{{ route('admin.reports.buffet.profitloss') }}">
            <i class="icon fa fa-dot-circle-o"></i>Profit and Loss (buffet)</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.cashRegister' ||
          Route::currentRouteName() == 'admin.reports.getCashRegister' ? 'active' : '' }}"
            href="{{ route('admin.reports.cashRegister') }}">
            <i class="icon fa fa-dot-circle-o"></i>Cash Register Sales (KOT)</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.buffet.cashRegister' ||
          Route::currentRouteName() == 'admin.reports.buffet.getCashRegister' ? 'active' : '' }}"
            href="{{ route('admin.reports.buffet.cashRegister') }}">
            <i class="icon fa fa-dot-circle-o"></i>Cash Register Sales (buffet)</a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.reports.digitalPayments' ||
            Route::currentRouteName() == 'admin.reports.getdigitalPayments' ? 'active' : '' }}"
            href="{{ route('admin.reports.digitalPayments') }}">
            <i class="icon fa fa-dot-circle-o"></i>Digital Payment Details</a>
        </li>
        @endcan


      </ul>
    </li>
    @endcan

    @can('manage-orders')
    @php if(Route::currentRouteName() == 'admin.sales.index' ||
    Route::currentRouteName() == 'admin.pos.orders.index' ||
    Route::currentRouteName() == 'admin.restaurant.sales.index'){
    $temp1 = 1;
    }else{
    $temp1 = 0;
    }
    @endphp
    <li class="{{ $temp1 ? 'treeview is-expanded' : 'treeview' }}">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-delicious"></i><span class="app-menu__label">KOT</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.restaurant.sales.index' ? 'active' : '' }}"
            href="{{ route('admin.restaurant.sales.index', 0) }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">KOT Food Management</span>
          </a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.pos.orders.index' ? 'active' : '' }}"
            href="{{ route('admin.pos.orders.index') }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">KOT Order Lists</span>
          </a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.sales.index' ? 'active' : '' }}"
            href="{{ route('admin.sales.index', 0) }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">KOT Checkout & Payment </span>
          </a>
        </li>
      </ul>
    </li>
    @php if(Route::currentRouteName() == 'admin.due.sales.index' ||
    Route::currentRouteName() == 'admin.due.orders.lists' || Route::currentRouteName() == 'admin.due.sales.paymentindex' ){
    $temp7 = 1;
    }else{
    $temp7 = 0;
    }
    @endphp
    <li class="{{ $temp7 ? 'treeview is-expanded' : 'treeview' }}">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-adjust"></i><span class="app-menu__label">KOT Due Sells</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.due.sales.index' ? 'active' : '' }}"
            href="{{ route('admin.due.sales.index') }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">Due Order Placement</span>
          </a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.due.orders.lists' ? 'active' : '' }}"
            href="{{ route('admin.due.orders.lists') }}">
            <i class="app-menu__icon fa fa-dot-circle-o" aria-hidden="true"></i>
            <span class="app-menu__label">Due Order Lists</span>
          </a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.due.sales.paymentindex' ? 'active' : '' }}"
            href="{{ route('admin.due.sales.paymentindex', 0) }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">Due KOT Checkout</span>
          </a>
        </li>
      </ul>
    </li>
    <li>
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.orders.index' ? 'active' : '' }}"
        href="{{ route('admin.orders.index') }}">
        <i class="app-menu__icon fa fa-bar-chart"></i>
        <span class="app-menu__label">Ecommerce Orders</span>
      </a>
    </li>
    @endcan

    @can('manage-stock')
    @php if(Route::currentRouteName() == 'admin.supplier.index' ||
    Route::currentRouteName() == 'admin.supplier.create' ||
    Route::currentRouteName() == 'admin.supplier.edit' ||
    Route::currentRouteName() == 'admin.supplier.stock.index' ||
    Route::currentRouteName() == 'admin.supplier.stock.create' ||
    Route::currentRouteName() == 'admin.supplier.stock.edit' ||
    Route::currentRouteName() == 'admin.supplier.requisition.index' ||
    Route::currentRouteName() == 'admin.supplier.requisition.create' ||
    Route::currentRouteName() == 'admin.supplier.requisition.edit' ||
    Route::currentRouteName() == 'admin.supplier.challan.create'||
    Route::currentRouteName() == 'admin.supplier.challan.index' ||
    Route::currentRouteName() == 'admin.supplier.return.index' ||
    Route::currentRouteName() == 'admin.supplier.return.create' ||
    Route::currentRouteName() == 'admin.product.disposal.create' ||
    Route::currentRouteName() == 'admin.product.disposal.index'){
    $temp_supplier = 1;
    }else{
    $temp_supplier = 0;
    }
    @endphp
    <li class="{{ $temp_supplier ? 'treeview is-expanded' : 'treeview' }}">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-cube"></i><span class="app-menu__label">Supplier and Dealings</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.supplier.stock.index'||
           Route::currentRouteName() == 'admin.supplier.stock.create'|| Route::currentRouteName() == 'admin.supplier.stock.edit' ? 'active' : '' }}"
            href="{{ route('admin.supplier.stock.index') }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">Manage Supplier Stock</span>
          </a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.supplier.index' ||
          Route::currentRouteName() == 'admin.supplier.create' || Route::currentRouteName() == 'admin.supplier.edit' ? 'active' : '' }}"
            href="{{ route('admin.supplier.index') }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">Manage Supplier</span>
          </a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.supplier.requisition.index' ||
          Route::currentRouteName() == 'admin.supplier.requisition.create' || Route::currentRouteName() == 'admin.supplier.requisition.edit'? 'active' : '' }}"
            href="{{ route('admin.supplier.requisition.index') }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">Requisition to Supplier</span>
          </a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.supplier.challan.create' ||
          Route::currentRouteName() == 'admin.supplier.challan.index' ? 'active' : '' }}"
            href="{{ route('admin.supplier.challan.create') }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">Receive from Supplier</span>
          </a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.supplier.return.index' ||
          Route::currentRouteName() == 'admin.supplier.return.create' ? 'active' : '' }}"
            href="{{ route('admin.supplier.return.create') }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">Return to Supplier</span>
          </a>
        </li>
        <li>
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.product.disposal.create' ||
          Route::currentRouteName() == 'admin.product.disposal.index' ? 'active' : '' }}"
            href="{{ route('admin.product.disposal.create') }}">
            <i class="app-menu__icon fa fa-dot-circle-o"></i>
            <span class="app-menu__label">Manage Disposal</span>
          </a>
        </li>
      </ul>
    </li>
    <li>
      {{-- if current route name is admin.settings we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.buffet.menu.index'  ||
      Route::currentRouteName() == 'admin.buffet.menu.create' || Route::currentRouteName() == 'admin.buffet.menu.edit'  ||
      Route::currentRouteName() == 'admin.buffet.menu.listorder' || Route::currentRouteName() == 'admin.buffet.sales.ordercheckout' ? 'active' : '' }}"
        href="{{ route('admin.buffet.menu.index')}}">
        <i class="app-menu__icon fa fa-object-group"></i>
        <span class="app-menu__label">Buffet Menu</span></a>
    </li>
    <li>
      {{-- if current route name is admin.categories.index we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.categories.index' ? 'active' : '' }}"
        href="{{ route('admin.categories.index')}}">
        <i class="app-menu__icon fa fa-tags"></i>
        <span class="app-menu__label">Food Category</span></a>
    </li>
    <li>
      {{-- if current route name is admin.products.index we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.products.index' ? 'active' : '' }}"
        href="{{ route('admin.products.index') }}">
        <i class="app-menu__icon fa fa-cutlery"></i>
        <span class="app-menu__label">Food Menu</span></a>
    </li>
    <li>
      {{-- if current route name is admin.settings we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.recipe.index' ? 'active' : '' }}"
        href="{{ route('admin.recipe.index')}}">
        <i class="app-menu__icon fa fa-modx"></i>
        <span class="app-menu__label">Recipes</span></a>
    </li>
    <li>
      {{-- if current route name is admin.settings we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.ingredientunit.index' ? 'active' : '' }}"
        href="{{ route('admin.ingredientunit.index')}}">
        <i class="app-menu__icon fa fa-object-ungroup"></i>
        <span class="app-menu__label">Unit Measurement</span></a>
    </li>
    <li>
      {{-- if current route name is admin.settings we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.ingredienttypes.index' ? 'active' : '' }}"
        href="{{ route('admin.ingredienttypes.index')}}">
        <i class="app-menu__icon fa fa-ils"></i>
        <span class="app-menu__label">Ingredients Types</span></a>
    </li>
    <li>
      {{-- if current route name is admin.settings we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.ingredient.index' ? 'active' : '' }}"
        href="{{ route('admin.ingredient.index')}}">
        <i class="app-menu__icon fa fa-th"></i>
        <span class="app-menu__label">Stock Ingredients(Recipe)</span></a>
    </li>
    
    @php if(Route::currentRouteName() == 'admin.services.create' ||
    Route::currentRouteName() == 'admin.services.index' ){
    $temp3 = 1;
    }else{
    $temp3 = 0;
    }
    @endphp
    <li class="{{ $temp3 ? 'treeview is-expanded' : 'treeview' }}">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-paw"></i><span class="app-menu__label">Manage Services</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <li>
          {{-- if current route name is admin.settings we will set active class here --}}
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.services.create' ? 'active' : '' }}"
            href="{{ route('admin.services.create')}}">
            <i class="app-menu__icon fa fa-crosshairs"></i>
            <span class="app-menu__label">Add Services</span></a>
        </li>
        <li>
          {{-- if current route name is admin.settings we will set active class here --}}
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.services.index' ? 'active' : '' }}"
            href="{{ route('admin.services.index') }}">
            <i class="app-menu__icon fa fa-crosshairs"></i>
            <span class="app-menu__label">All Services</span></a>
        </li>
      </ul>
    </li>
    
    @endcan

    @can('all-admin-features')
    
    <li>
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.complimentary.sales.index' ? 'active' : '' }}"
        href="{{ route('admin.complimentary.sales.index') }}">
        <i class="app-menu__icon fa fa-leaf"></i>
        <span class="app-menu__label">Complimentary POS</span>
      </a>
    </li>
    @php if(Route::currentRouteName() == 'admin.districts.index' ||
    Route::currentRouteName() == 'admin.zones.index' ||
    Route::currentRouteName() == 'admin.zones.getall' ){
    $temp4 = 1;
    }else{
    $temp4 = 0;
    }
    @endphp
    <li class="{{ $temp4 ? 'treeview is-expanded' : 'treeview' }}">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-map-marker"></i><span class="app-menu__label">Area Coverage</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <li>
          {{-- if current route name is dashboard we will set active class  --}}
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.districts.index' ? 'active' : ''}}"
            href="{{route('admin.districts.index')}}">
            <i class="app-menu__icon fa fa-map-signs"></i>
            <span class="app-menu__label">Manage District</span>
          </a>
        </li>
        <li>
          {{-- if current route name is dashboard we will set active class  --}}
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.zones.index' ||
          Route::currentRouteName() == 'admin.zones.getall' ? 'active' : ''}}" href="{{route('admin.zones.index')}}">
            <i class="app-menu__icon fa fa-map-signs"></i>
            <span class="app-menu__label">Manage Area</span>
          </a>
        </li>
      </ul>
    </li>
    
    <li>
      {{-- if current route name is admin.settings we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.payment.gw.index' ? 'active' : '' }}"
        href="{{ route('admin.payment.gw.index')}}">
        <i class="app-menu__icon fa fa-credit-card"></i>
        <span class="app-menu__label">Payment GW</span></a>
    </li>
    <li>
      {{-- if current route name is admin.settings we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.gpstar.index' ? 'active' : '' }}"
        href="{{ route('admin.gpstar.index')}}">
        <i class="app-menu__icon fa fa-mobile fa-2x"></i>
        <span class="app-menu__label">GP Star Discount</span></a>
    </li>
    <li>
      {{-- if current route name is admin.settings we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.settings' ? 'active' : '' }}"
        href="{{ route('admin.settings')}}">
        <i class="app-menu__icon fa fa-cogs"></i>
        <span class="app-menu__label">Settings</span></a>
    </li>

    @endcan

    @can('super-admin')
    @php if(Route::currentRouteName() == 'admin.adduser.form' ||
    Route::currentRouteName() == 'admin.users.index' ){
    $temp2 = 1;
    }else{
    $temp2 = 0;
    }
    @endphp
    <li class="{{ $temp2 ? 'treeview is-expanded' : 'treeview' }}">
      <a class="app-menu__item" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-users"></i><span class="app-menu__label">Manage Users</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
        <li>
          {{-- if current route name is admin.settings we will set active class here --}}
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.adduser.form' ? 'active' : '' }}"
            href="{{ route('admin.adduser.form') }}">
            <i class="app-menu__icon fa fa-user"></i>
            <span class="app-menu__label">Add User</span></a>
        </li>
        <li>
          {{-- if current route name is admin.settings we will set active class here --}}
          <a class="treeview-item {{ Route::currentRouteName() == 'admin.users.index' ? 'active' : '' }}"
            href="{{ route('admin.users.index') }}">
            <i class="app-menu__icon fa fa-user"></i>
            <span class="app-menu__label">Manage Users & Roles</span></a>
        </li>
      </ul>
    </li>
    <li>
      {{-- if current route name is admin.settings we will set active class here --}}
      <a class="app-menu__item {{ Route::currentRouteName() == 'admin.board.directors.index' ? 'active' : '' }}"
        href="{{ route('admin.board.directors.index')}}">
        <i class="app-menu__icon fa fa-podcast"></i>
        <span class="app-menu__label">Discount References</span></a>
    </li>

    @endcan





  </ul>
</aside>
