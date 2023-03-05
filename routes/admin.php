<?php 

/*
|--------------------------------------------------------------------------
| Admin Web Routes
|--------------------------------------------------------------------------
|
| Here is where Admin user can register web routes for this application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['namespace' => 'Admin','prefix' => 'admin', 'as' => 'admin.'], function(){

    // We are adding a routes group to prefix all our admin routes with /admin. so that get route would be /admin/login.
    // we are adding a routes group to as with named route so that name route would be ('admin.login.post');
    // we are adding a routes group to namespace with controller so that controller name would be Admin\LoginController@showLoginForm.
    
    // admin login request
    Route::get('login', 'LoginController@showLoginForm')->name('login'); 
    Route::post('login', 'LoginController@login')->name('login.post');  
    Route::get('logout', 'LoginController@logout')->name('logout');  
    
    // admin password reset form display and email send
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    // admin password reset
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('password.update');

    // To block unauthoized incoming HTTP requests we use middleware.
    // also we have to put this route at the last. 
    // Protecting the dashboard route, so only authenticated admin can load the dashboard view.
    // here auth:admin is admin guard.
    Route::group(['middleware' => ['auth:admin']], function () {

        Route::group(['middleware' => ['can:funville-dashboard']], function () {        
            Route::get('/', function () {
                return view('admin.dashboard.index');
            })->name('dashboard');
        });


        Route::group(['middleware' => ['can:manage-orders']], function () { 
            // Ecommerce Order Management
            Route::group(['prefix' => 'orders'], function () {
                Route::get('/', 'OrderController@index')->name('orders.index');
                Route::get('/edit/{id}', 'OrderController@edit')->name('orders.edit');
                Route::post('/update', 'OrderController@update')->name('orders.update');
                // Route::get('/{order}/show', 'OrderController@show')->name('orders.show');
                Route::get('/search', 'OrderController@search')->name('orders.search');
                Route::get('/invoice/{id}', 'OrderController@generateInvoice')->name('orders.invoice');
            });
            //KOT route checkout and orderplacement and  customer print receipt
            Route::group(['prefix' => 'pos'], function(){
                Route::get('/sales/{id}', 'SalesController@index')->name('sales.index'); 
                //search products using table no.
                Route::get('/search', 'SalesController@search')->name('sales.search');
                //pos order place
                //Route::post('/orderplace', 'SalesController@orderplace')->name('sales.orderplace');
                Route::post('/orderupdate', 'SalesController@orderupdate')->name('sales.orderupdate');
                //ajax route for pos sales.           
                Route::post('/getfoods','SalesController@getFoods')->name('sales.getfoods');
                Route::post('/foods/addsales','SalesController@addToSales')->name('sales.addtosales');
                Route::post('/cart/update', 'SalesController@update')->name('sales.saleCartUpdate');
                Route::post('/cart/delete', 'SalesController@destroy')->name('sales.saleCartDelete');

                Route::post('/customer/mobile','SalesController@getMobileNo')->name('sales.customermobile');
                Route::post('/customer/info','SalesController@addCustomerInfo')->name('sales.customerInfo');
                Route::post('/discount/slab','SalesController@discountSlab')->name('sales.discountSlab');
                Route::post('/card/discount','SalesController@cardDiscount')->name('sales.card.discount'); 
                Route::post('/gpstar/discount','SalesController@gpStarDiscount')->name('sales.gpStarDiscount'); 
                //end of ajax route for pos sales.
            
            });

            //POS/KOT route for restaurant print receipt
            Route::group(['prefix' => 'kot'], function(){
                Route::get('/restaurant/{id}', 'PosRestaurantController@index')->name('restaurant.sales.index'); 
                //pos order place
                Route::post('/order-place', 'PosRestaurantController@orderplace')->name('restaurant.sales.orderplace');
                
                //ajax route for pos sales.           
                Route::post('/findfoods','PosRestaurantController@getFoods')->name('restaurant.sales.getfoods');
                Route::post('/findfoods/addsales','PosRestaurantController@addToSales')->name('restaurant.sales.addtosales');
                Route::post('/sale-cart/update', 'PosRestaurantController@update')->name('restaurant.sales.saleCartUpdate');
                Route::post('/sale-cart/delete', 'PosRestaurantController@destroy')->name('restaurant.sales.saleCartDelete');
                //end of ajax route for pos sales.
            
            });

            // // KOT route for restaurant--- is no more used.
            // Route::group(['prefix' => 'kot'], function(){
            //     Route::get('/food/management', 'KotRestaurantController@index')->name('restaurant.sales.index'); 
               
            //     //ajax route for KOT [pos sales].    
            //     Route::get('/table/getfoods/{id}', 'KotRestaurantController@getTableFoods')->name('restaurant.sales.gettablefoods'); 
            //     //add single food item to a table     
            //     Route::post('/findfoods','KotRestaurantController@getFoods')->name('restaurant.sales.getfoods');
            //     Route::post('/findfoods/addsales','KotRestaurantController@addToSales')->name('restaurant.sales.addtosales');
            //     Route::post('/sale-cart/update', 'KotRestaurantController@update')->name('restaurant.sales.saleCartUpdate');
            //     Route::post('/sale-cart/delete', 'KotRestaurantController@destroy')->name('restaurant.sales.saleCartDelete');
            //     //end of ajax route for KOT [ pos sales].
            // });


            //POS Orders Management [list of all pos orders]-- here POS=KOT
            Route::group(['prefix' => 'pos/orders'], function () {
                Route::get('/', 'PosOrderController@index')->name('pos.orders.index');
                Route::get('/edit/{id}', 'PosOrderController@edit')->name('pos.orders.edit');
                // Route::post('/update', 'PosOrderController@update')->name('pos.orders.update');
                Route::get('/search', 'PosOrderController@search')->name('pos.orders.search');
                //ajax method for update order status
                Route::post('/status', 'PosOrderController@orderStatusUpdate');                                          
                Route::post('/searchfoods','PosOrderController@getFoods')->name('pos.sales.getfoods');
                Route::post('/searchfoods/addsales','PosOrderController@addToSales')->name('pos.sales.addtosales');
                Route::post('/sale-cart/update', 'PosOrderController@update')->name('pos.sales.saleCartUpdate');
                Route::post('/sale-cart/delete', 'PosOrderController@destroy')->name('pos.sales.saleCartDelete');
                //end of ajax route for pos sales.
            });

        });

        // inventory controller rule
        Route::group(['middleware' => ['can:manage-stock']], function () { 

            //all Ingredient stock
            Route::group(['prefix' => 'ingredients'], function(){
                Route::get('/', 'IngredientController@index')->name('ingredient.index');
                Route::get('/create', 'IngredientController@create')->name('ingredient.create');
                Route::post('/store', 'IngredientController@store')->name('ingredient.store');
                Route::get('/edit/{id}', 'IngredientController@edit')->name('ingredient.edit');
                Route::post('/update', 'IngredientController@update')->name('ingredient.update');
                Route::get('/delete/{id}', 'IngredientController@delete')->name('ingredient.delete');                
            });

            // ingredient purchase or damage ajax route to autocomplete ingredient name.
            Route::post('/getingredients','IngredientController@getIngredients')->name('ingredient.getingredients');

            // ingredient purchase
            Route::group(['prefix' => 'ingredients/purchase'], function(){
                Route::get('/{id}', 'IngredientPurchaseController@index')->name('ingredient.purchase.index');
                Route::get('/{id}/create', 'IngredientPurchaseController@create')->name('ingredient.purchase.create');
                Route::post('/store', 'IngredientPurchaseController@store')->name('ingredient.purchase.store');
                Route::get('/{id}/edit', 'IngredientPurchaseController@edit')->name('ingredient.purchase.edit');
                Route::post('/update', 'IngredientPurchaseController@update')->name('ingredient.purchase.update');
                Route::get('/{id}/delete', 'IngredientPurchaseController@delete')->name('ingredient.purchase.delete');
            });
            

            // ingredient Damage
            Route::group(['prefix' => 'ingredients/damage'], function(){
                Route::get('/{id}', 'IngredientDamageController@index')->name('ingredient.damage.index');
                Route::get('/{id}/create', 'IngredientDamageController@create')->name('ingredient.damage.create');
                Route::post('/store', 'IngredientDamageController@store')->name('ingredient.damage.store');
                Route::get('/{id}/edit', 'IngredientDamageController@edit')->name('ingredient.damage.edit');
                Route::post('/update', 'IngredientDamageController@update')->name('ingredient.damage.update');
                Route::get('/{id}/delete', 'IngredientDamageController@delete')->name('ingredient.damage.delete');
            });

            // supplier and its all transactions/dealings
            Route::group(['prefix' => 'supplier'], function(){
                Route::get('/', 'SupplierController@index')->name('supplier.index');
                Route::get('/create', 'SupplierController@create')->name('supplier.create');
                Route::post('/store', 'SupplierController@store')->name('supplier.store');
                Route::get('/edit/{id}', 'SupplierController@edit')->name('supplier.edit');
                Route::post('/update', 'SupplierController@update')->name('supplier.update');
                Route::get('/delete/{id}', 'SupplierController@delete')->name('supplier.delete');
            });
            
            //supplier stock 
            Route::group(['prefix' => 'supplier/stock'], function(){
                Route::get('/', 'SupplierStockController@index')->name('supplier.stock.index');
                Route::get('/create', 'SupplierStockController@create')->name('supplier.stock.create');
                Route::post('/store', 'SupplierStockController@store')->name('supplier.stock.store');
                Route::get('/edit/{id}', 'SupplierStockController@edit')->name('supplier.stock.edit');
                Route::post('/update', 'SupplierStockController@update')->name('supplier.stock.update');
                // Route::get('/delete/{id}', 'SupplierStockController@delete')->name('supplier.stock.delete');
                // ajax route for getting stock items
                Route::get('/getproducts','SupplierStockController@getSupplierProducts')->name('supplier.getproducts');
                Route::get('/recipe/ingredient/{id}','SupplierStockController@getUnitfromIngredientId');
                Route::get('/types/ingredient/{id}','SupplierStockController@getIngredientsByType');
                
            });

            //Requisition to supplier
            Route::group(['prefix' => 'supplier/requisition'], function(){
                Route::get('/', 'SupplierRequisitionController@index')->name('supplier.requisition.index');
                Route::get('/create', 'SupplierRequisitionController@create')->name('supplier.requisition.create');
                Route::post('/store', 'SupplierRequisitionController@store')->name('supplier.requisition.store');
                Route::get('/edit/{id}', 'SupplierRequisitionController@edit')->name('supplier.requisition.edit');
                Route::post('/update', 'SupplierRequisitionController@update')->name('supplier.requisition.update');  
                Route::get('/pdf/{id}', 'SupplierRequisitionController@generateRequisitionPdf')->name('supplier.requisition.pdf');              
                // ajax route for getting stock items
                Route::get('/getall','SupplierRequisitionController@getSupplierRequisition')->name('supplier.requisitions');
                Route::get('/allproducts/{id}','SupplierRequisitionController@getAllSupplierProducts');
                Route::get('/product/details/{id}','SupplierRequisitionController@getProductUnit');
                Route::get('/recipe/ingredient/{id}','SupplierRequisitionController@getRecipeUnit');
                
            });

            //Delivery challan received from supplier
            Route::group(['prefix' => 'supplier/challan'], function(){
                Route::get('/', 'DeliveryChallanController@index')->name('supplier.challan.index');
                Route::get('/create', 'DeliveryChallanController@create')->name('supplier.challan.create');
                Route::post('/store', 'DeliveryChallanController@store')->name('supplier.challan.store');             
                Route::get('/pdf/{id}', 'DeliveryChallanController@generateChallanPdf')->name('supplier.challan.pdf');               
                // ajax route for getting stock items
                Route::get('/get/product-details/{id}','DeliveryChallanController@getProductDetail');
                Route::get('/get/ingredient_units/{id}','DeliveryChallanController@getRecipeIngredientUnits'); 
                Route::get('/requisitions/{id}','DeliveryChallanController@getRequisitions');
                Route::get('/{dt1}/{dt2}/{supplier}','DeliveryChallanController@getRequisitionsFromDateWithSupplier');
                Route::get('/get-Requisition/{id}/','DeliveryChallanController@getOnlyRequisition'); 
                Route::get('/getall','DeliveryChallanController@getSupplierChallan')->name('supplier.challans');
            });

            //products Return to supplier            
            Route::group(['prefix' => 'supplier/return'], function(){
                Route::get('/', 'SupplierReturnController@index')->name('supplier.return.index');
                Route::get('/create', 'SupplierReturnController@create')->name('supplier.return.create');
                Route::post('/store', 'SupplierReturnController@store')->name('supplier.return.store');                
                Route::get('/pdf/{id}', 'SupplierReturnController@generateReturnPdf')->name('supplier.return.pdf');              
                // ajax route for getting stock items                
                Route::get('/allproducts/{id}','SupplierReturnController@getAllSupplierProducts');  
                Route::get('/getall','SupplierReturnController@getSupplierReturn')->name('supplier.returns');                             
            });

            //Product Disposal
            Route::group(['prefix' => 'product/disposal'], function(){
                Route::get('/', 'ProductDisposalController@index')->name('product.disposal.index');
                Route::get('/create', 'ProductDisposalController@create')->name('product.disposal.create');
                Route::post('/store', 'ProductDisposalController@store')->name('product.disposal.store');              
                // ajax route for getting stock items                
                Route::get('/recipe/ingredients/{id}','ProductDisposalController@getRecipeIngredientSupplierProducts'); 
                Route::get('/recipe-quantity/{id}/{qty}','ProductDisposalController@getRecipeIngredientQty'); 
                Route::get('/pdf/{id}', 'ProductDisposalController@generateDisposalPdf')->name('product.disposal.pdf'); 
                Route::get('/getall','ProductDisposalController@getProductDisposal')->name('product.disposals'); 
            });

            //Buffet list
            Route::group(['prefix' => 'buffet/menu'], function(){
                Route::get('/','BuffetMenuController@index')->name('buffet.menu.index');
                Route::get('/create', 'BuffetMenuController@create')->name('buffet.menu.create');
                Route::post('/store', 'BuffetMenuController@store')->name('buffet.menu.store');
                Route::get('/edit/{id}', 'BuffetMenuController@edit')->name('buffet.menu.edit');
                Route::post('/update','BuffetMenuController@update')->name('buffet.menu.update');
                Route::get('/order/{id}/{order_id?}', 'BuffetMenuController@createOrder')->name('buffet.menu.createOrder');
                Route::post('/orderplace', 'BuffetMenuController@orderplace')->name('buffet.menu.orderPlace');
                Route::get('/list/order', 'BuffetMenuController@orderlist')->name('buffet.menu.listorder');
                Route::get('/search', 'BuffetMenuController@searchOrder')->name('buffet.orders.search');
                Route::get('/orderlist/edit-order/{id}', 'BuffetMenuController@orderEdit')->name('buffet.order.edit');
                Route::get('/orders/{id}/{order_id?}', 'BuffetMenuController@ordercheckout')->name('buffet.sales.ordercheckout');                 
                // buffet POS
                //search products using table no.
                Route::get('/order-search', 'BuffetMenuController@search')->name('buffet.kot.search');                
                Route::post('/orderupdate', 'BuffetMenuController@orderupdate')->name('buffet.kot.orderupdate');

                //ajax method for buffet update order status
                Route::post('/order/status', 'BuffetMenuController@orderStatusUpdate');
                
            });

            //buffet food list
            Route::group(['prefix' => 'buffet/recipe'], function(){
                Route::get('/{id}', 'BuffetRecipeController@index')->name('buffet.recipe.index');
                Route::get('/{id}/create', 'BuffetRecipeController@create')->name('buffet.recipe.create');
                Route::post('/store', 'BuffetRecipeController@store')->name('buffet.recipe.store');
                Route::get('/edit/{id}', 'BuffetRecipeController@edit')->name('buffet.recipe.edit');
                Route::post('/update', 'BuffetRecipeController@update')->name('buffet.recipe.update');
                Route::get('/delete/{id}', 'BuffetRecipeController@delete')->name('buffet.recipe.delete');
            });
            
            // List categories route /admin/categories
            // Create category route /admin/categories/create : for creating category form
            // Store category route /admin/categories/store : storing the data into the database
            // Edit category route /admin/categories/{id}/edit : displaying the edit form by searching category id 
            // Update category route /admin/categories/update: update that category id row data 
            // Delete category route /admin/categories/{id}/delete : delete a particular category 

            Route::group(['prefix' => 'categories'], function(){

                Route::get('/', 'CategoryController@index')->name('categories.index');
                Route::get('/create', 'CategoryController@create')->name('categories.create');
                Route::post('/store', 'CategoryController@store')->name('categories.store');
                Route::get('/edit/{id}', 'CategoryController@edit')->name('categories.edit');
                Route::post('/update', 'CategoryController@update')->name('categories.update');
                Route::get('/delete/{id}', 'CategoryController@delete')->name('categories.delete');
            });

            Route::group(['prefix' => 'products'], function(){
                Route::get('/', 'ProductController@index')->name('products.index');
                Route::get('/create', 'ProductController@create')->name('products.create');
                Route::post('/store', 'ProductController@store')->name('products.store');
                Route::get('/{id}/edit', 'ProductController@edit')->name('products.edit');
                Route::post('/update', 'ProductController@update')->name('products.update');
                Route::get('/{id}/delete', 'ProductController@delete')->name('products.delete');
            });
            // creating the required routes for image uploading and delete using dropzone.
            Route::group(['prefix' => 'images'], function(){
                Route::post('/upload', 'ProductImageController@upload')->name('products.images.upload');
                Route::get('/{id}/delete', 'ProductImageController@delete')->name('products.images.delete');
            });
            
            Route::group(['prefix' => 'attributes'], function(){   
                // list all the attributes of the current product     
                Route::get('/{id}', 'ProductAttributeController@index')->name('products.attribute.index');
                // create attribute form for the current product
                Route::get('/{id}/create', 'ProductAttributeController@create')->name('products.attribute.create');
                // Add product attribute to the current product
                Route::post('/store', 'ProductAttributeController@store')->name('products.attribute.store');
                // edit product attribute to the current product
                Route::get('/{id}/edit', 'ProductAttributeController@edit')->name('products.attribute.edit');
                // update product attribute to the current product
                Route::post('/update', 'ProductAttributeController@update')->name('products.attribute.update');
                // Delete product attribute from the current product
                Route::get('/{id}/delete', 'ProductAttributeController@delete')->name('products.attribute.delete');
            });

            // Add Services
            Route::group(['prefix' => 'services'], function(){  
            Route::get('/create', 'ServiceController@create')->name('services.create'); 
            Route::post('/store', 'ServiceController@store')->name('services.store');
            Route::get('/all', 'ServiceController@index')->name('services.index'); 
            Route::get('/{id}/edit','ServiceController@edit')->name('services.edit');
            Route::post('/update', 'ServiceController@update')->name('services.update');
            Route::get('/{id}/delete', 'ServiceController@delete')->name('services.delete');
            });

            //Ingredient unit measurement
            Route::group(['prefix' => 'ingredient-units'], function(){
                Route::get('/', 'IngredientUnitController@index')->name('ingredientunit.index');
                Route::get('/create', 'IngredientUnitController@create')->name('ingredientunit.create');
                Route::post('/store', 'IngredientUnitController@store')->name('ingredientunit.store');
                Route::get('/edit/{id}', 'IngredientUnitController@edit')->name('ingredientunit.edit');
                Route::post('/update', 'IngredientUnitController@update')->name('ingredientunit.update');
                Route::get('/delete/{id}', 'IngredientUnitController@delete')->name('ingredientunit.delete');
            });

            //Ingredient Types
            Route::group(['prefix' => 'ingredient-types'], function(){
                Route::get('/', 'IngredientTypesController@index')->name('ingredienttypes.index');
                Route::get('/create', 'IngredientTypesController@create')->name('ingredienttypes.create');
                Route::post('/store', 'IngredientTypesController@store')->name('ingredienttypes.store');
                Route::get('/edit/{id}', 'IngredientTypesController@edit')->name('ingredienttypes.edit');
                Route::post('/update', 'IngredientTypesController@update')->name('ingredienttypes.update');
                Route::get('/delete/{id}', 'IngredientTypesController@delete')->name('ingredienttypes.delete');
            });
            
            //Recipe
            Route::group(['prefix' => 'recipe'], function(){
                Route::get('/', 'RecipeController@index')->name('recipe.index');
                Route::get('/create', 'RecipeController@create')->name('recipe.create');
                Route::post('/store', 'RecipeController@store')->name('recipe.store');
                Route::get('/edit/{id}', 'RecipeController@edit')->name('recipe.edit');
                Route::post('/update', 'RecipeController@update')->name('recipe.update');
                Route::get('/delete/{id}', 'RecipeController@delete')->name('recipe.delete');
            });

            //Recipe Ingredients
            Route::group(['prefix' => 'recipe/ingredients'], function(){                
                Route::get('/{id}', 'RecipeIngredientController@index')->name('recipe.ingredient.index');
                Route::get('/{id}/create', 'RecipeIngredientController@create')->name('recipe.ingredient.create');
                Route::post('/store', 'RecipeIngredientController@store')->name('recipe.ingredient.store');
                Route::get('/edit/{id}', 'RecipeIngredientController@edit')->name('recipe.ingredient.edit');
                Route::post('/update', 'RecipeIngredientController@update')->name('recipe.ingredient.update');
                Route::get('/delete/{id}', 'RecipeIngredientController@delete')->name('recipe.ingredient.delete');
                //ajax route for get smallest unit measurement
                Route::post('/getunit','RecipeIngredientController@getunit')->name('recipe.ingredient.getunit');
            });


        });

        Route::group(['middleware' => ['can:manage-reports']], function () { 
            //start ecommerce reports            
            Route::get('/reports/ecom/profit-loss', 'ReportController@profitloss')->name('reports.ecom.profitloss');
            Route::post('/reports/ecom/profit-loss', 'ReportController@getprofitloss')->name('reports.ecom.getprofitloss'); 
            Route::get('/reports/ecom/cash-register', 'ReportController@cashregister')->name('reports.ecom.cashregister');
            Route::post('/reports/ecom/cash-register', 'ReportController@getcashregister')->name('reports.ecom.getcashregister');                         
            Route::get('/reports/single', 'ReportController@single')->name('reports.single');
            Route::post('/reports/single', 'ReportController@singleSale')->name('reports.singleSale');

            //pdf-reports
            Route::get('/reports/ecom/pdf/cash-register/{date1}/{date2}', 'ReportController@pdfgetcashregister')->name('reports.ecom.pdfgetcashregister');
            Route::get('/reports/ecom/pdf/profit-loss/{date1}/{date2}/{op}', 'ReportController@pdfgetprofitloss')->name('reports.ecom.pdfgetprofitloss');
            //Route::get('/reports/single/pdf/{date1}/{date2}/{search}', 'ReportController@pdfSingleSale')->name('reports.pdfSingleSale'); 

            //excel reports
            //Route::get('/reports/single/excel/{date1}/{date2}/{search}', 'ReportController@excelSingleSale')->name('reports.excelSingleSale');
            //end of ecommerce reports

            //MIS Reports            
            Route::get('/reports/combined/profit-loss', 'MISReportController@combinedprofitLoss')->name('reports.combined.profitLoss');
            Route::post('/reports/combined/profit-loss', 'MISReportController@getcombinedprofitLoss')->name('reports.combined.getcombinedprofitLoss');
            Route::get('/reports/profit-loss', 'MISReportController@profitLoss')->name('reports.profitLoss');
            Route::post('/reports/profit-loss', 'MISReportController@getprofitloss')->name('reports.getprofitloss');             
            Route::get('/reports/cash-register', 'MISReportController@cashRegister')->name('reports.cashRegister');
            Route::post('/reports/cash-register', 'MISReportController@getCashRegister')->name('reports.getCashRegister');
            Route::get('/reports/customer-sales', 'MISReportController@customerSales')->name('reports.customerSales');
            Route::post('/reports/customer-sales', 'MISReportController@getCustomerSales')->name('reports.getCustomerSales'); 
            Route::get('/reports/sales-complimentary', 'MISReportController@complimentarySales')->name('reports.complimentarySales');
            Route::post('/reports/sales-complimentary', 'MISReportController@getcomplimentarySales')->name('reports.getcomplimentarySales'); 
            Route::get('/reports/bonus-point', 'MISReportController@bonusPoint')->name('reports.bonusPoint');
            Route::get('/reports/stock', 'MISReportController@stock')->name('reports.stock');
            Route::post('/reports/stock', 'MISReportController@getstock')->name('reports.getstock');
            Route::get('/reports/digital/payments', 'MISReportController@digitalPayments')->name('reports.digitalPayments');
            Route::post('/reports/digital/payments', 'MISReportController@getdigitalPayments')->name('reports.getdigitalPayments'); 
            Route::get('/reports/ingredient/purchase', 'MISReportController@ingredientPurchase')->name('reports.ingredientPurchase'); 
            Route::post('/reports/ingredient/purchase', 'MISReportController@getingredientPurchase')->name('reports.getingredientPurchase');
            Route::get('/reports/kot/ref-discount', 'MISReportController@refDiscount')->name('reports.refDiscount'); 
            Route::post('/reports/kot/ref-discount', 'MISReportController@getrefDiscount')->name('reports.getrefDiscount');
	        Route::get('/reports/kot/due/total-sales', 'MISReportController@dueSalesTotal')->name('reports.due.salesTotal'); 
            Route::post('/reports/kot/due/total-sales', 'MISReportController@getDueSalesTotal')->name('reports.due.getsalesTotal');

            //Supplier Reports.
            Route::get('/reports/supplier/requisition', 'MISReportController@requisition')->name('reports.supplier.requisition');
            Route::post('/reports/supplier/requisition', 'MISReportController@getrequisition')->name('reports.supplier.getrequisition');
            Route::get('/reports/supplier/challan', 'MISReportController@challan')->name('reports.supplier.challan');
            Route::post('/reports/supplier/challan', 'MISReportController@getchallan')->name('reports.supplier.getchallan');
            Route::get('/reports/supplier/return', 'MISReportController@return')->name('reports.supplier.return');
            Route::post('/reports/supplier/return', 'MISReportController@getreturn')->name('reports.supplier.getreturn');
            Route::get('/reports/product/disposal', 'MISReportController@disposal')->name('reports.product.disposal');
            Route::post('/reports/product/disposal', 'MISReportController@getdisposal')->name('reports.product.getdisposal');

            //buffet Reports
            Route::get('/reports/buffet-profit-loss', 'MISReportController@buffetprofitLoss')->name('reports.buffet.profitloss');
            Route::post('/reports/buffet-profit-loss', 'MISReportController@buffetgetprofitloss')->name('reports.buffet.getprofitloss');
            Route::get('/reports/buffet/cash-register', 'MISReportController@cashRegisterBuffet')->name('reports.buffet.cashRegister');
            Route::post('/reports/nuffet/cash-register', 'MISReportController@getCashRegisterBuffet')->name('reports.buffet.getCashRegister'); 
            //combined vat report
            Route::get('/reports/combined/vat', 'MISReportController@combinedvat')->name('reports.combined.vat');
            Route::post('/reports/combined/vat', 'MISReportController@getcombinedvat')->name('reports.combined.getvat');
            //modified profit-loss/vat
            Route::get('/reports/profit-loss-m', 'MISReportController@modprofitLoss')->name('reports.percentage.profitLoss');
            Route::post('/reports/profit-loss-m', 'MISReportController@getmodifiedprofitloss')->name('reports.percentage.getprofitLoss');
            Route::get('/reports/vat-m', 'MISReportController@modifiedvat')->name('reports.percentage.vat');
            Route::post('/reports/vat-m', 'MISReportController@getmodifiedvat')->name('reports.percentage.getvat');
            //modified cash register & buffet cash register
            Route::get('/reports/cash-register-m', 'MISReportController@modcashRegister')->name('reports.percentage.cashRegister');
            Route::post('/reports/cash-register-m', 'MISReportController@modgetCashRegister')->name('reports.percentage.getCashRegister');
            Route::get('/reports/buffet/cash-register-m', 'MISReportController@modcashRegisterBuffet')->name('reports.buffet.percentage.cashRegister');
            Route::post('/reports/buffet/cash-register-m', 'MISReportController@modgetCashRegisterBuffet')->name('reports.buffet.percentage.getCashRegister');

            //ajax call to search customer via select2.
            Route::post('/reports/getclients/', 'MISReportController@getClients')->name('reports.getClients');
            //MIS PDF reports
            Route::get('/reports/combined/pdf/profit-loss/{date1}/{date2}', 'MISReportController@pdfcombinedgetprofitloss')->name('reports.pdfcombinedgetprofitloss');
            Route::get('/reports/pdf/cash-register/{date1}/{date2}', 'MISReportController@pdfgetCashRegister')->name('reports.pdfgetCashRegister');            
            Route::get('/report/pdf/customer-points/', 'MISReportController@pdfgetBonusPoints')->name('reports.pdfgetBonusPoints');
            Route::get('/report/pdf/customer-sales/{date1}/{date2}/{id}', 'MISReportController@pdfgetCustomerSales')->name('reports.pdfgetCustomerSales');
            Route::get('/reports/pdf/profit-loss/{date1}/{date2}/{op}', 'MISReportController@pdfgetprofitloss')->name('reports.pdfgetprofitloss');
            Route::get('/reports/pdf/sales-complimentary/{date1}/{date2}', 'MISReportController@pdfcomplimentarySales')->name('reports.pdfcomplimentarySales');
            Route::get('/report/pdf/stock/{op}', 'MISReportController@pdfstock')->name('reports.pdfstock');
            Route::get('/reports/pdf/digital-payments/{date1}/{date2}/{op}', 'MISReportController@pdfgetDigitalPayment')->name('reports.pdfgetDigitalPayment');
            Route::get('/reports/pdf/ingredient/purchase/{date1}/{date2}', 'MISReportController@pdfgetingredient')->name('reports.pdfgetingredient');
            Route::get('/reports/pdf/kot/ref-discount/{date1}/{date2}', 'MISReportController@pdfrefDiscount')->name('reports.pdfrefDiscount');
	        Route::get('/reports/pdf/kot/due-sales/{date1}/{date2}', 'MISReportController@pdfDueSalesTotal')->name('reports.pdfDueSalesTotal');

            //Supplier Requisition Report: pdf
            Route::get('/reports/pdf/requisition/{date1}/{date2}/{type}/{id}', 'MISReportController@pdfRequisition')->name('reports.pdfRequisition');
            Route::get('/reports/pdf/receiving/{date1}/{date2}/{type}/{id}', 'MISReportController@pdfReceiving')->name('reports.pdfReceiving');
            Route::get('/reports/pdf/return/{date1}/{date2}/{type}/{id}', 'MISReportController@pdfReturn')->name('reports.pdfReturn');
            Route::get('/reports/pdf/dispose/{date1}/{date2}', 'MISReportController@pdfDispose')->name('reports.pdfDispose');

            //buffet reports:pdf
            Route::get('/reports/buffet/pdf/profit-loss/{date1}/{date2}', 'MISReportController@pdfbuffetgetprofitloss')->name('reports.buffet.pdfgetprofitloss');
            Route::get('/reports/buffet/pdf/cash-register/{date1}/{date2}', 'MISReportController@pdfbuffetgetCashRegister')->name('reports.buffet.pdfgetCashRegister');  
            //vat pdf report
            Route::get('/reports/combined/pdf/vat/{date1}/{date2}', 'MISReportController@pdfcombinedgetvat')->name('reports.pdfcombinedgetvat');
            //modified pdf vat report
            Route::get('/reports/pdf/profit-loss-m/{date1}/{date2}', 'MISReportController@pdfmodifiedgetprofitloss')->name('reports.pdfmodifiedgetprofitloss');
            Route::get('/reports/combined-m/pdf/vat/{date1}/{date2}', 'MISReportController@pdfmodifiedgetvat')->name('reports.pdfmodifiedgetvat');
            //modified cash register & buffet cash register report
            Route::get('/reports/pdf/cash-register-m/{date1}/{date2}', 'MISReportController@modpdfgetCashRegister')->name('reports.percentage.pdfgetCashRegister'); 
            Route::get('/reports/buffet/pdf/cash-register-m/{date1}/{date2}', 'MISReportController@modpdfbuffetgetCashRegister')->name('reports.buffet.percentage.pdfgetCashRegister');
        });

        //admin role
        Route::group(['middleware' => ['can:all-admin-features']], function () {  

            //  for all settings we will use one controller : SettingController
            Route::get('/settings', 'SettingController@index')->name('settings');
            Route::post('/settings', 'SettingController@update')->name('settings.update');
            // for manage Districts 
            Route::get('/districts', 'DistrictController@index')->name('districts.index');
            Route::post('/districts', 'DistrictController@districtUpdate');
            
            // for manage Zones 
            Route::get('/districts/zones', 'ZoneController@index')->name('zones.index');
            Route::get('/districts/zones/{id}', 'ZoneController@getZones')->name('zones.getall');
            Route::post('/districts/zones', 'ZoneController@zoneUpdate');
            

            //Payment Gateways
            Route::group(['prefix' => 'payment/gw'], function(){
                Route::get('/', 'PaymentGWController@index')->name('payment.gw.index');
                Route::get('/create', 'PaymentGWController@create')->name('payment.gw.create');
                Route::post('/store', 'PaymentGWController@store')->name('payment.gw.store');
                Route::get('/edit/{id}', 'PaymentGWController@edit')->name('payment.gw.edit');
                Route::post('/update', 'PaymentGWController@update')->name('payment.gw.update');
                Route::get('/delete/{id}', 'PaymentGWController@delete')->name('payment.gw.delete');
            });  
            
            //GP Star discount
            Route::group(['prefix' => 'gpstar/discount'], function(){
                Route::get('/', 'GpStarController@index')->name('gpstar.index');
                Route::get('/create', 'GpStarController@create')->name('gpstar.create');
                Route::post('/store', 'GpStarController@store')->name('gpstar.store');
                Route::get('/edit/{id}', 'GpStarController@edit')->name('gpstar.edit');
                Route::post('/update', 'GpStarController@update')->name('gpstar.update');
                Route::get('/delete/{id}', 'GpStarController@delete')->name('gpstar.delete');
            });
            

            //Complimentary sales route for restaurant.
            
            Route::group(['prefix' => 'complimentary'], function(){
                Route::get('/sales', 'ComplimentarySaleController@index')->name('complimentary.sales.index'); 
                //order place
                Route::post('/order-place', 'ComplimentarySaleController@orderplace')->name('complimentary.sales.orderplace');
                
                //ajax route.           
                Route::post('/findfoods','ComplimentarySaleController@getFoods')->name('complimentary.sales.getfoods');
                Route::post('/findfoods/addsales','ComplimentarySaleController@addToSales')->name('complimentary.sales.addtosales');
                Route::post('/sales/update', 'ComplimentarySaleController@update')->name('complimentary.sales.saleCartUpdate');
                Route::post('/sales/delete', 'ComplimentarySaleController@destroy')->name('complimentary.sales.saleCartDelete');
                //end of ajax route.
            });

	    //only admin privileges users can sell foods in dues
            //POS/KOT route for restaurant print receipt
            Route::group(['prefix' => 'due/kot'], function(){
                Route::get('/sells', 'DuePosSalesController@index')->name('due.sales.index'); 
                //pos due order place
                Route::post('/orderPlace', 'DuePosSalesController@orderplace')->name('due.sales.orderplace'); 
            });

            //POS/KOT Due Orders Management [list of all due kot/pos orders]
            Route::group(['prefix' => 'kot/orders'], function () {
                Route::get('/', 'DuePosSalesController@orderLists')->name('due.orders.lists');
                Route::get('/edit/{id}', 'DuePosSalesController@editDueOrder')->name('due.orders.edit');                
                Route::get('/search', 'DuePosSalesController@search')->name('due.orders.search');
                //ajax method for update order status
                Route::post('/status', 'DuePosSalesController@orderStatusUpdate');                                          
                Route::post('/searchfoods','DuePosSalesController@getFoods')->name('due.sales.getfoods');
                Route::post('/searchfoods/addsales','DuePosSalesController@addToSales')->name('due.sales.addtosales');                
                Route::post('/duesale-cart/update', 'DuePosSalesController@update')->name('due.sales.saleCartUpdate');
                Route::post('/duesale-cart/delete', 'DuePosSalesController@destroy')->name('due.sales.saleCartDelete');
                //end of ajax route for pos sales.
            });

            //KOT route checkout and orderplacement and  customer print receipt
            Route::group(['prefix' => 'kot/due'], function(){
                Route::get('/sales/{id}', 'DuePosSalesController@paymentindex')->name('due.sales.paymentindex'); 
                //search products using table no.
                Route::get('/search', 'DuePosSalesController@searchDueCart')->name('due.sales.search');
                //ajax route for due pos sales.
                Route::post('/customer/mobile','DuePosSalesController@getMobileNo')->name('due.sales.customermobile');
                Route::post('/customer/info','DuePosSalesController@addCustomerInfo')->name('due.sales.customerInfo');
                Route::post('/discount/slab','DuePosSalesController@discountSlab')->name('due.sales.discountSlab');
                Route::post('/card/discount','DuePosSalesController@cardDiscount')->name('due.sales.card.discount'); 
                Route::post('/gpstar/discount','DuePosSalesController@gpStarDiscount')->name('due.sales.gpStarDiscount'); 
                
                Route::post('/orderupdate', 'DuePosSalesController@orderupdate')->name('due.sales.orderupdate'); 
            
            });


        });
        //super admin role.
        Route::group(['middleware' => ['can:super-admin']], function () {  
            
            // Add Users  
            Route::get('/adduser', 'AddUserController@showAddUserForm')->name('adduser.form'); 
            Route::post('/adduser', 'AddUserController@saveUser')->name('adduser.save'); 
            // Users Role  
            Route::get('/role/users', 'RoleUserController@index')->name('users.index');
            Route::get('/role/users/{id}/edit','RoleUserController@edit')->name('users.edit');
            Route::post('/role/users', 'RoleUserController@update')->name('users.update');
            Route::get('/role/users/{id}', 'RoleUserController@destroy')->name('users.destroy');
            // Route::resource('/role/users', 'RoleUserController', ['except' => ['show','create', 'store']]);           

            //Discount Refrences
            Route::group(['prefix' => 'directors'], function(){
                Route::get('/', 'DirectorController@index')->name('board.directors.index');
                Route::get('/create', 'DirectorController@create')->name('board.directors.create');
                Route::post('/store', 'DirectorController@store')->name('board.directors.store');
                Route::get('/edit/{id}', 'DirectorController@edit')->name('board.directors.edit');
                Route::post('/update', 'DirectorController@update')->name('board.directors.update');
                Route::get('/delete/{id}', 'DirectorController@delete')->name('board.directors.delete');
            });


        });


        
    });
    

});
