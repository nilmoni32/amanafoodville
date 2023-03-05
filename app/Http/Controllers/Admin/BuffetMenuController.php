<?php

namespace App\Http\Controllers\Admin;

use App\Models\Unit;
use App\Models\Buffet;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Ingredient; 
use App\Models\Client;
use App\Models\Buffetorder;
use App\Models\Buffetsale;
use App\Models\SupplierStock;
use App\Models\Buffetsalebackup;
use App\Models\Ordersalepayment;
use App\Models\BuffetRecipe;
use Illuminate\Http\Request;
use App\Traits\FlashMessages;
use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseController;
use Auth;
use Carbon\Carbon;
use DateTime;


class BuffetMenuController extends BaseController
{
    use FlashMessages;

    public function index(){
        // Attaching pagetitle and subtitle to view.        
        view()->share(['pageTitle' => 'Buffets', 'subTitle' => 'List of all Buffet Menu']);
        $buffets = Buffet::orderBy('created_at', 'desc')->get();
        return view('admin.buffets.index', compact('buffets'));
    }

    public function create(){        
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Buffets', 'subTitle' => 'Create a buffet package']);        
        return view('admin.buffets.create');  
    }

    /**
     * Save the buffet
     * @param Request $request  
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){
        
        $validated = $request->validate([
            'buffet_name'                   => 'required|string|max:191',            
            'buffet_guest_list'             => 'required|numeric|gt:0',                     
        ]);

        $buffet = new Buffet();
        $buffet->buffet_name = $request->buffet_name;
        $buffet->buffet_guest_list = $request->buffet_guest_list;
        $buffet->save();
        
        if($buffet){ 
            // setting flash message using trait
            $this->setFlashMessage(' A new buffet is added successfully', 'success');    
            $this->showFlashMessages();
            return redirect()->route('admin.buffet.menu.index');

        }else{
            return $this->responseRedirectBack(' Error occurred while adding the buffet .' ,'error', false, false);    
        }

    }

    public function edit($id){    
        $buffet = Buffet::find($id);
        view()->share(['pageTitle' => 'Buffets', 'subTitle' => 'Update the Buffet Details']);
        return view('admin.buffets.edit', compact('buffet'));
    }

    /**
     * Update the ingredient
     * @param Request $request  
     * @throws \Illuminate\Validation\ValidationException
     */

    public function update(Request $request){
        
        $validated = $request->validate([
            'buffet_name'                   => 'required|string|max:191',            
            'buffet_guest_list'             => 'required|numeric|gt:0', 
            'unit_sale_price'               => 'required|numeric|gt:0'     
        ]);
         
        //getting the buffet sales record on the current date
        $buffet_sales_backup = Buffetsalebackup::where('buffet_id', $request->buffet_id)->whereDate('created_at', date('Y-m-d'))->first();     
        $buffet_sales = Buffetsale::where('buffet_id', $request->buffet_id)->whereDate('created_at', date('Y-m-d'))->first();

        if($buffet_sales || $buffet_sales_backup){
            return $this->responseRedirectBack(' Update is not possible after the buffet sale has started on a day.' ,'error', false, false);
        }else if(!$buffet_sales || !$buffet_sales_backup){
    
            $buffet= Buffet::find($request->buffet_id);
            $buffet->buffet_name = $request->buffet_name;
            $buffet->buffet_guest_list = $request->buffet_guest_list;
            $buffet->unit_sale_price = $request->unit_sale_price;
            $buffet->buffet_guest_list_served = 0;
            $buffet->save();
        }
        
        if($buffet){ 
            // setting flash message using trait
            $this->setFlashMessage(' Buffet is Updated successfully', 'success');    
            $this->showFlashMessages();
            return redirect()->route('admin.buffet.menu.index');

        }else{
            return $this->responseRedirectBack(' Error occurred while updating the buffet .' ,'error', false, false);    
        }

    }

    public function createOrder($id, $order_id = 0){
        $buffet = Buffet::find($id);        
        view()->share(['pageTitle' => 'Buffets- '.$buffet->buffet_name, 'subTitle' => 'Place an order' ]);
        return view('admin.buffets.orderplace', ['buffet' => $buffet, 'order_id' => $order_id ]);
    }

    public function orderplace(Request $request){
        $validated = $request->validate([
            'order_tableNo'        => 'required|string|max:191',            
            'guest_no'             => 'required|numeric|gt:0',
        ]);

        $buffet_id = $request->input('buffet_id');
        $guest_to_be_served = $request->input('guest_no');
        $order_tableNo = $request->input('order_tableNo');
        
        //checking the table no for usability   
        if(Buffetorder::where('order_tableNo', $order_tableNo)->first()){
            // setting flash message using trait
            $this->setFlashMessage(" Your selected table '".$order_tableNo."' is currently in use, please select another table", 'error');    
            $this->showFlashMessages(); 
            return redirect()->back();
        }
        //finding the buffet
        $buffet = Buffet::find($buffet_id);

        //checking buffet has set its cost price and sale price
        if($buffet->unit_sale_price == null ||  $buffet->unit_sale_price <= 0 || $buffet->unit_cost_price == null ||  
            $buffet->unit_cost_price <= 0){
            // setting flash message using trait
            $this->setFlashMessage("Please set Buffet unit sale price and cost price by adding foods into the buffet", 'error');    
            $this->showFlashMessages(); 
            return redirect()->back();
        }

        // before buffet order takes place checking the buffet recipe stock ingredient total quantity is zero or negative.
        foreach($buffet->buffetRecipes as $buffetRecipe){            
            foreach($buffetRecipe->recipe->recipeingredients as $ingredient){
                if(Ingredient::find($ingredient->ingredient_id)->total_quantity <= 0){
                    // setting flash message using trait
                    $this->setFlashMessage("Please purchase the ingredient  '". 
                    Ingredient::find($ingredient->ingredient_id)->name ."' of food '". $buffetRecipe->recipe->product->name ."' before sale.", 'error');    
                    $this->showFlashMessages(); 
                    return redirect()->back();
                }
            }
        }

        $buffet->buffet_guest_list_served += $guest_to_be_served;
        $buffet->save();

        

        // finding last order id: we use it for customer order id (customized) for billing purpose
        // it will be false only for the first record.
        if(!Buffetorder::orderBy('id', 'desc')->first()){
            $ord_id = 0;
        }
        else{
            $ord_id = Buffetorder::orderBy('id', 'desc')->first()->id; 
        }   
        $ord_id = '#'.(1000 + ($ord_id + 1));

        $order = new Buffetorder(); // we use order_id as online transaction id.
        $order->admin_id = auth()->user()->id;     
        $order->order_number = $ord_id;         
        $order->order_date = \Carbon\Carbon::now()->toDateTimeString();         
        $order->order_tableNo = $order_tableNo;
        $order->save();
        
        // create a buffetsale record
        $sale = new Buffetsale();
        $sale->admin_id = Auth::id(); 
        $sale->buffet_id = $buffet_id;
        $sale->product_name = $buffet->buffet_name;
        $sale->unit_price = $buffet->unit_sale_price;
        $sale->production_food_cost = $buffet->unit_cost_price;
        $sale->product_quantity = $guest_to_be_served;
        $sale->buffetorder_id  = $order->id;
        $sale->order_tbl_no = $order_tableNo;
        $sale->save();
        
        // setting flash message using trait
        $this->setFlashMessage(' Buffet order is placed successfully', 'success');    
        $this->showFlashMessages(); 

        return redirect()->route('admin.buffet.menu.createOrder', ['id' => $buffet_id, 'order_id' => $order->id ]);
        
    }

    public function orderlist(){
        view()->share(['pageTitle' => 'Buffets', 'subTitle' => 'List of all Buffet Menu Orders']);        
        $orders = Buffetorder::orderBy('created_at', 'desc')->paginate(30);        
        return view('admin.buffets.orderlist', ['orders' => $orders]);
    }

    public function orderStatusUpdate(Request $request){
        $order = Buffetorder::find($request->id);
        $order->status = $request->status;
        if($request->status == 'cancel'){
            $order->order_tableNo = NULL;
        }
        $order->save();

        $buffet_sale_cart_backup = [];
        $buffet_sale = Buffetsale::where('buffetorder_id', $order->id)->get();        
        foreach($buffet_sale as $buffet_sale_cart){
            $buffet_cart_backup = [
                'buffet_id'             => $buffet_sale_cart->buffet_id,
                'product_id'            => $buffet_sale_cart->product_id,
                'admin_id'              => $buffet_sale_cart->admin_id,
                'buffetorder_id'        => $buffet_sale_cart->buffetorder_id,
                'product_name'          => $buffet_sale_cart->product_name,
                'product_quantity'      => $buffet_sale_cart->product_quantity,
                'unit_price'            => $buffet_sale_cart->unit_price,
                'production_food_cost'  => $buffet_sale_cart->production_food_cost,
                'order_cancel'          => 1,
                'order_tbl_no'          => $order->order_tableNo,
                'created_at'            => Carbon::now()->toDateTimeString(),
                'updated_at'            => Carbon::now()->toDateTimeString(),
            ];            
            $buffet_sale_cart_backup[] = $buffet_cart_backup;

            //reducing buffet guest list served count
            $buffet = Buffet::find($buffet_sale_cart->buffet_id);
            $buffet->buffet_guest_list_served -= $buffet_sale_cart->product_quantity;
            $buffet->save();
        } 
        \DB::table('buffetsalebackups')->insert($buffet_sale_cart_backup);
        
        //Now Deleting record from buffet sale table in order to free up space 
        foreach($buffet_sale as $buffet_sale_cart){
            $buffet_sale_cart->delete();
        }      
        return response()->json(['status' => 'success']);  
    }

    public function searchOrder(Request $request){
        $search = strtolower(trim($request->search)); // getting the search key
        
        //search criteria.      
        $orders = Buffetorder::orWhere('order_number', 'like', '%'.$search.'%') 
        ->orWhere('order_tableNo', 'like', '%'.$search.'%')      
        ->orWhere('order_date', 'like', '%'. ($this->validateDateTime($search) ? Carbon::createFromFormat('d-m-Y H:i:s', $search)->format('Y-m-d H:i:s') : $search).'%')   
        ->orWhere('order_date', 'like', '%'. ($this->validateDate($search) ? Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d') : $search).'%')   
        ->orWhere('grand_total', 'like', '%'.$search.'%') 
        ->orWhere('status', 'like', '%'.$search.'%')     
        ->orWhere('payment_method', 'like', '%'.$search.'%')->paginate(10); 
         
         // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Buffet Orders', 'subTitle' => 'List of Search Buffet Orders' ]);       
        return view('admin.buffets.orderlist', ['orders' => $orders]);
    }

    public function validateDateTime($date, $format = 'd-m-Y H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
 
    public function validateDate($date, $format = 'd-m-Y')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function ordercheckout($id, $order_id = 0){
        // Attaching pagetitle and subtitle to view.        
        view()->share(['pageTitle' => 'Buffet Orders', 'subTitle' => 'Order Checkout and Payment' ]);
        $buffet = Buffet::find($id);
        return view('admin.buffets.checkout', ['buffet' => $buffet, 'order_id' => $order_id]);
    }
    
    public function search(Request $request){
        $search = trim($request->search); // getting the search key          
       // search criteria.      /orders/{id}/{order_id?}
        $order = Buffetorder::orWhere('order_number', 'like', '%'.$search.'%')
                ->orWhere('order_tableNo', 'like', '%'.$search.'%')->first();
        if($order){
            return redirect()->route('admin.buffet.sales.ordercheckout', ['id' => $request->buffet_id, 'order_id' => $order->id]); 
        }
        else{
            return $this->responseRedirectBack(' Sorry, the order table no is not found!' ,'error', false, false); 
        }
    }

    public function orderupdate(Request $request){ 
        //dd($request->all());
        $this->validate($request,[  
            // 'order_tableNo'    => 'nullable|string|max:10',
            'customer_name'     => 'nullable|string|max:40',             
            'customer_mobile'   => 'nullable|regex:/(01)[3-9]{1}(\d){8}/|max:11',
            'customer_address'  => 'nullable|string|max:191',       
            'customer_notes'    => 'nullable|string|max:191',             
        ]);
        
        /*
        * storing all the payment details to db table ordersalepayments and cal culating cash_pay, card_pay or mobile_pay.
        */
        $order_payments = '';
        // As each order might have single card discount or mobile discount, so we store it directly.        
        $card_discount = 0;
        $fraction_discount = 0; 
        //calculating cash_pay, card_pay & mobile_banking_pay for the current order.       
        $cash_pay = 0; $card_pay = 0; $mobile_banking_pay = 0;
        //Convert JSON String to PHP Array     
        $order_payments = json_decode($request->payment_details, true);        
        if(empty($order_payments) || empty($order_payments[0]['customerPaid'])) {
            // setting flash message using trait
            $this->setFlashMessage("Order payments can't be null", 'error');    
            $this->showFlashMessages();
            return redirect()->back(); 
        }

        foreach($order_payments as $single_payment_details){
            //creating a new instance of ordersalepayments table          
            $ordersalepayment = new Ordersalepayment();
            $ordersalepayment->ordersale_id = $single_payment_details['saleOrderId'];
            $ordersalepayment->payment_method = $single_payment_details['paymentMethod'];
            $ordersalepayment->bank_name = $single_payment_details['bankName'];
            $ordersalepayment->card_discount = $single_payment_details['cardDiscount'];
            $ordersalepayment->customer_paid_amount = $single_payment_details['customerPaid'];
            $ordersalepayment->cash_exchange = $single_payment_details['due'];
            //store_paidamount = Customer paid + due as due can be negative value that means 500 + due(-50) = 450 taka will be stored.
            $ordersalepayment->store_paidamount = $single_payment_details['customerPaid'] + $single_payment_details['due'];            
            $ordersalepayment->save();

            //storing card discount
            if(!$card_discount){
                $card_discount = $single_payment_details['cardDiscount'];
            }

            //storing fraction discount
            if(!$fraction_discount){
                $fraction_discount = $single_payment_details['fractionDiscount'];
            }           
           
            //calculating cash_pay, card_pay & mobile_banking_pay for the current order. 
            //C-1: when due = 0 with multimode payments.
            if(!$single_payment_details['due']) {
                if($single_payment_details['paymentMethod'] == 'cash'){
                    $cash_pay += $single_payment_details['customerPaid'];
                }elseif($single_payment_details['paymentMethod'] == 'card'){
                    $card_pay += $single_payment_details['customerPaid'];
                }elseif($single_payment_details['paymentMethod'] == 'mobile'){
                    $mobile_banking_pay += $single_payment_details['customerPaid'];
                }
            }
            //C-2: when due is less tha 0 (due < 0): customer has received cash exchanged.
            if($single_payment_details['due'] < 0) {
                // Calculating actual paid amount paid by the customer.
                $actual_paid_amount = $single_payment_details['customerPaid'] + $single_payment_details['due'];

                if($single_payment_details['paymentMethod'] == 'cash'){
                    $cash_pay += $actual_paid_amount;
                }elseif($single_payment_details['paymentMethod'] == 'card'){
                    $card_pay += $actual_paid_amount;
                }elseif($single_payment_details['paymentMethod'] == 'mobile'){
                    $mobile_banking_pay += $actual_paid_amount;
                }
            }           
            
        }//end of foreach loop.

        //Order Update: Discount, reward points discount, Payment Details, Customer Points
        $order = Buffetorder::where('id', $request->order_id)->first();
        $order->admin_id = auth()->user()->id;     
        //$order->order_number = $ord_id; 
        $order->discount = $request->order_discount; //referene discount.
        $order->reward_discount = $request->reward_discount;
        $order->director_id = $request->order_discount_reference;        
        $order->order_date = \Carbon\Carbon::now()->toDateTimeString(); 
        $order->payment_method = implode(',', $request->payment_method); // making array to string before saving to database.
        $order->cash_pay = $cash_pay;
        $order->card_pay = $card_pay;
        $order->mobile_banking_pay = $mobile_banking_pay;
        $order->card_discount = $card_discount; //either card or mobile bank discount will store.
        $order->order_tableNo = NULL;//$request->order_tableNo; as that table no should be free to feed another customer.   
        $order->status = 'delivered';
        $order->fraction_discount = $fraction_discount;
        $order->gpstarmobile_no = $request->gpstarmobile;
        $order->gpstar_discount = $request->gpstar_discount;

        //calculating order total + tax, if exists
        $order_total = $request->subtotal + (config('settings.tax_percentage') ? ($request->subtotal * (config('settings.tax_percentage')/100)) : 0);
        //calculating vat for this order
        $order->vat = config('settings.tax_percentage') ? ($request->subtotal * (config('settings.tax_percentage')/100)) : 0;
        //substracting director reference discount        
        $order_discount_total = $request->order_discount ?  $order_total - $request->order_discount : $order_total;
        //substracting reward point discount
        $order_grand_total = $request->reward_discount ? $order_discount_total - $request->reward_discount : $order_discount_total;  
        //substracting card discount
        $order_grand_total = $card_discount ? $order_grand_total - $card_discount : $order_grand_total;  
        //substracting fraction discount
        $order_grand_total = $fraction_discount ? $order_grand_total - $fraction_discount : $order_grand_total; 
        //substracting gpstar discount
        $order_grand_total = $request->gpstar_discount ? $order_grand_total - $request->gpstar_discount : $order_grand_total;

        //grand total after substracting all the discount options      
        $order->grand_total = $order_grand_total;
        // if customer data is not available we will not create the customer details.
        if($request->customer_mobile){    
            //checking if client not exists, we create client here to store client information. 
            $client = $request->total_points ? Client::where('mobile', $request->customer_mobile)->first() : new Client();        
            $client->name = $request->customer_name;        
            $client->mobile = $request->customer_mobile;
            $client->address = $request->customer_address;
            $client->notes = $request->customer_notes;
            // customer points calculation.        
            $client->total_points += $order_total / (config('settings.money_to_point'));
            //if reward discount is used, we will set client total_points to zero.
            $client->total_points = $request->reward_discount ? 0 : $client->total_points;
            $client->save();
            //saving client id to order table
            $order->client_id = $client->id;
        }

        //hide order total amount   
        $order->hide_order_total = config('settings.hide_order_amount') ?? 0; 
        //updating the order
        $order->save();

        
        //Inventory Management: We will deduct product quantity and product total cost using product id from ingredient stock. 

         //finding the cart using order id... it may return many sale carts for pos system
         foreach($order->buffetsales as $cart){
            //getting product quantity that user has purchased.
            $cart_product_quantity = $cart->product_quantity;
            foreach(Buffet::find($cart->buffet_id)->buffetRecipes as $buffet_recipe){
                //using product id finding the recipe and then finding the ingredients of the recipe
                foreach(Recipe::find($buffet_recipe->recipe_id)->recipeingredients as $recipeingredient){
                    //getting the ingredient.
                    $ingredient = $recipeingredient->ingredient;
                    /*start of updating recipe stock */
                    //Subtracting ingredient total cost from ingredient stock consumed in recipe ingredients. 
                    $ingredient->total_price -= ($recipeingredient->ingredient_total_cost * $cart_product_quantity);
                    // if ingredient stock unit is equal to recipe ingredients... then we just deduct qty from ingredient stock.
                    if($ingredient->measurement_unit == $recipeingredient->measure_unit){
                        $ingredient->total_quantity -= ($recipeingredient->quantity * $cart_product_quantity); 
                    }else{
                        // getting unit conversion value from Unit 
                        $unit = Unit::where('smallest_measurement_unit', $recipeingredient->measure_unit)->first();            
                        $unit_conversion = $unit->unit_conversion; 
                        $ingredient->total_quantity -= ($recipeingredient->quantity * $cart_product_quantity/$unit_conversion);
                    }
                    $ingredient->save();
                    /* end of updating recipe stock */
                    
                    /*start of updating supplier stock */
                    //recipe single ingredient total cost
                    $recipe_ingredient_total_cost = $recipeingredient->ingredient_total_cost * $cart_product_quantity;
                    //recipe single ingredient total qty which user has ordered for a food item 
                    $recipe_ingredient_total_qty = $recipeingredient->quantity * $cart_product_quantity;

                    //getting all supplier products of the corresponding ingredient
                    $supplier_stocks = SupplierStock::where('ingredient_id', $ingredient->id)->get();
                    //getting number of supplier stock products of the corresponding ingredient.
                    $no_of_records = $supplier_stocks->count();

                    foreach($supplier_stocks as $supplier_stock){                    
                        //subtracting supplier stock product total cost of the corresponding ingredient of the food recipe.
                        if($ingredient->total_price <= 0){
                            $supplier_stock->total_cost = 0;
                        }else{
                            $supplier_stock->total_cost -= $recipe_ingredient_total_cost/$no_of_records;
                        }                    
                        //substracting supplier stock quantity.                    
                        //getting supplier stock unit & unit conversion 
                        $supplier_stock_unit = Unit::where('measurement_unit', $supplier_stock->measurement_unit)->first();
                        $stk_small_measurement_unit = $supplier_stock_unit->smallest_measurement_unit;
                        $conversion_unit = $supplier_stock_unit->unit_conversion;
                        //when supplier product have no differ product unit
                        if(!$supplier_stock->has_differ_product_unit && $recipeingredient->measure_unit == $supplier_stock->measurement_unit){
                            $supplier_stock->total_qty -= ($recipe_ingredient_total_qty)/$no_of_records;
                        }else if(!$supplier_stock->has_differ_product_unit && $recipeingredient->measure_unit == $stk_small_measurement_unit){
                            $supplier_stock->total_qty -= (($recipe_ingredient_total_qty)/floatval($conversion_unit))/$no_of_records;
                        }//and for other cases when supplier product have differ product unit & supplier product ingredient quantity become zero or less than zero. 
                        else if($recipeingredient->measure_unit != $supplier_stock->measurement_unit && $ingredient->total_quantity <= 0){
                            $supplier_stock->total_qty = 0;
                        }
                        
                        $supplier_stock->save();
                    }

                    /*end of updating supplier stock */
                

                }
            }

        }

	//BACKUP of POS sales: Making pos sale backup to Salebackup table 
        $buffet_cart_backup = [];   
        $buffet_sale = Buffetsale::where('buffetorder_id', $order->id)->get();      
        foreach($buffet_sale as $saleCart){
            $cart_backup = [
                'buffet_id'             => $saleCart->buffet_id,
                'product_id'            => $saleCart->product_id,
                'admin_id'              => $saleCart->admin_id,
                'buffetorder_id'        => $saleCart->buffetorder_id,
                'product_name'          => $saleCart->product_name,
                'product_quantity'      => $saleCart->product_quantity,
                'unit_price'            => $saleCart->unit_price,
                'production_food_cost'  => $saleCart->production_food_cost,
                'order_cancel'          => $saleCart->order_cancel,
                'order_tbl_no'          => $saleCart->order_tbl_no,
                'created_at'            => $saleCart->created_at,
                'updated_at'            => $saleCart->updated_at,
            ];            
            $buffet_cart_backup[] = $cart_backup;
        } 
        \DB::table('buffetsalebackups')->insert($buffet_cart_backup);
        //Now Deleting record from pos sale table in order to free up space to pos sale table
        foreach($buffet_sale as $saleCart){
            $saleCart->delete();
        } 
        // setting flash message using trait
        $this->setFlashMessage(' Order is updated successfully', 'success');    
        $this->showFlashMessages(); 
        
        // return redirect()->route('admin.sales.index', $order->id);
        return redirect()->route('admin.buffet.sales.ordercheckout', ['id' => $request->buffet_id, 'order_id' => $order->id]);
    }

    

}
