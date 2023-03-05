<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Duesale;
use App\Models\Unit;
use App\Models\Recipe;
use App\Models\Product;
use App\Models\Category;
use App\Models\Dueordersale;
use App\Models\Ordersale;
use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Traits\FlashMessages; 
use App\Http\Controllers\Controller;
use App\Models\Client;
use DateTime;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;
use App\Models\Ordersalepayment;
use App\Models\SupplierStock;

class DuePosSalesController extends BaseController
{
    use FlashMessages;

    public function index(){
        //Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Kitchen Order Ticketing System', 'subTitle' => 'Place an order with customer details with advance payment' ]);
        return view('admin.sales.due.index');        
    }
   
    public function orderplace(Request $request){
      
        $this->validate($request,[  
            //'order_tableNo'    => 'required|string|max:10',
            'booked_money'     => 'required|regex:/^\d+(\.\d{1,2})?$/|gt:'.config('settings.due_booking_amount'),
            // 'payment_date'     => 'required|string',
            'customer_name'     => 'required|string|max:50',             
            'customer_mobile'   => 'required|regex:/(01)[3-9]{1}(\d){8}/|max:11',
            'customer_address'  => 'nullable|string|max:191',       
            'customer_notes'    => 'nullable|string|max:191', 
        ]);
        
        //checking the table no for usability   
        // if(Dueordersale::where('order_tableNo', $request->order_tableNo)->first()){
        //     // setting flash message using trait
        //     $this->setFlashMessage(" Your selected table '".$request->order_tableNo."' is currently in use, please select another table", 'error');    
        //     $this->showFlashMessages(); 
        //     return redirect()->back();
        // }

        //checking if client not exists, we create client here to store client information. 
        $client = Client::where('mobile', $request->customer_mobile)->first() ?? new Client();        
        $client->name = $request->customer_name;        
        $client->mobile = $request->customer_mobile;
        if($request->customer_address){
            $client->address = $request->customer_address;
        }
        if($request->customer_notes){
            $client->notes = $request->customer_notes;
        }        
        $client->save();

        // finding last order id: we use it for customer order id (customized) for billing purpose
        // it will be false only for the first record.
        if(!Dueordersale::orderBy('id', 'desc')->first()){
            $ord_id = 0;
        }
        else{
            $ord_id = Dueordersale::orderBy('id', 'desc')->first()->id; 
        }   
        $ord_id = '#'.(10000 + ($ord_id + 1));

        $order = new Dueordersale(); // we use order_id as online transaction id.
        $order->admin_id = auth()->user()->id;     
        $order->order_number = $ord_id;         
        $order->order_date = \Carbon\Carbon::now()->toDateTimeString();         
        //$order->order_tableNo = $request->order_tableNo;
        $order->booked_money = $request->booked_money;
        $order->receive_total = $request->booked_money; // initial receive total amount
        $order->payment_date = \Carbon\Carbon::now()->toDateTimeString();  
        // saving client id to order table
        $order->client_id = $client->id;
        $order->save();              
        
        // setting flash message using trait
        $this->setFlashMessage('Order with advance payment is created successfully', 'success');    
        $this->showFlashMessages(); 
        
        return redirect()->route('admin.due.sales.index');   
       
    } 

    public function orderLists(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'KOT Due Lists', 'subTitle' => 'List of all Due KOT Orders' ]);
        $orders = Dueordersale::orderBy('created_at', 'desc')->paginate(30);
        return view('admin.sales.due.orderlists', compact('orders')); 
    }

    public function editDueOrder($id){
        // Attaching pagetitle and subtitle to view.          
        $order = Dueordersale::where('id', $id)->first();
        view()->share(['pageTitle' => 'KOT Due Orders', 'subTitle' => 'KOT Order No: '.$order->order_number ]);
        return view('admin.sales.due.editDueOrder', compact('order'));
    }   

    /*
    * Ajax request
    */
    public function getFoods(Request $request){

        $search = $request->search;

        if($search == ''){
            $products = Product::orderby('name','asc')
                                ->select('id','name')
                                ->where('status', 1)
                                ->limit(10)->get();
        }else{
            $products = Product::orderby('name','asc')
                        ->select('id','name')
                        ->where('name', 'like', '%' .$search . '%')
                        ->where('status', 1)
                        ->limit(10)
                        ->get();
        }

        $response = array();
        foreach($products as $product){            
            $response[] = array( "value" => $product->id, "label" => $product->name );            
        }

        return response()->json($response);   
    }

    public function calculateSubtotal($id){
        // calculating subtotal
        $total_taka=0.0;
        foreach(Duesale::where('admin_id', Auth::id())->where('dueordersale_id',$id)->get() as $sale){      
          
            if($sale->product->discount_price){
                    $total_taka += $sale->product->discount_price * $sale->product_quantity;
            }else{
                    $total_taka += $sale->product->price * $sale->product_quantity;
            }         
        }
        return $total_taka;
    }


    public function addToSales(Request $request){

        $product = Product::find($request->foodId);
        //checking discount price is enabled for this product
        if($product->discount_price){
            $sale_price = $product->discount_price;
        }else{
            $sale_price = $product->price;
        }
        // checking the food is added to the recipe
        if(!Recipe::where('product_id', $request->foodId)->first()){
            return json_encode([ 'status' => 'info', 'message' => "Please add '". $request->foodName ."' food recipe before you sale." ]);           
         }// recipe is added but recipe ingredients is not added for the food
         elseif(!Recipe::where('product_id', $request->foodId)->first()->recipeingredients->count()){          
             return json_encode([ 'status' => 'info', 'message' => "Please add '". $request->foodName ."' food recipe ingredients before you  sale." ]);
         }
         else{
            //when stock ingredient total quantity is zero or negative after sales.
            foreach(Recipe::where('product_id', $request->foodId)->first()->recipeingredients as $ingredient){
               if(Ingredient::where('id', $ingredient->ingredient_id)->first()->total_quantity <= 0){
                    return json_encode([ 'status' => 'info', 'message' => "Please add purchase record for ingredient '". 
                    Ingredient::where('id', $ingredient->ingredient_id)->first()->name ."' of food '". $request->foodName ."' before sale." ]);
                }
            }
            
         }

        //checking the product whether it is already added to the sale cart, if so we sent the message this product is already added to the cart.
        $sale = Duesale::where('admin_id', Auth::id())
                    ->where('product_id', $request->foodId)
                    ->where('dueordersale_id', $request->orderId) 
                    ->first();
        if(!is_null($sale)){
            return json_encode([ 'status' => 'info', 'message' => "This food is already added to the cart."  ]);
        }
        else{
            // adding new item to the due sales cart
            // Create a cart for due sells and store dueordersale_id & order_tbl_no
            $sale = new Duesale();
            $sale->admin_id = Auth::id(); 
            $sale->product_id = $request->foodId;
            $sale->product_name = $request->foodName;
            $sale->dueordersale_id = $request->orderId;
            $sale->order_tbl_no = $request->orderTableNo;
            $sale->unit_price = $sale_price;
            $sale->product_quantity = 1;
            $sale->save();

            return json_encode([ 'status' => 'success', 'foodname' => $sale->product_name , 'price' => $sale->unit_price, 'qty' => $sale->product_quantity, 'id' => $sale->id , 'sub_total' => $this->calculateSubtotal($sale->dueordersale_id) ]);
            
        }          
        
    }

    public function update(Request $request)
    {  
        $id = $request->sale_id;                
        $sale = Duesale::find($id);//primary key id
        $sale->product_quantity = $request->product_quantity;           
        $sale->save();        
        if($sale->product->discount_price){
                $total_unit_price = $sale->product->discount_price * $sale->product_quantity;
        }else{
                $total_unit_price = $sale->product->price * $sale->product_quantity;
        }
         
        return json_encode([ 'status' => 'success', 'total_unit_price' => $total_unit_price, 'sub_total' => $this->calculateSubtotal($sale->dueordersale_id) ]);      
    }

    public function destroy(Request $request)
    {
        $sale = Duesale::find($request->sale_id);
        if(!is_null($sale)){            
            $sale->delete();
            return json_encode([ 'status' => 'success', 'message' => 'Item is deleted', 'sub_total' => $this->calculateSubtotal($sale->dueordersale_id) ]); 
        }
        return json_encode(['status' => 'error', 'message' => 'An error is occurred while deleting the cart' ]);
    }

    /*
    * Ajax request
    */
    public function orderStatusUpdate(Request $request){
        $order = Dueordersale::find($request->id);
        $order->status = $request->status;
        if($request->status == 'cancel'){
            $order->order_tableNo = NULL;
        }
        $order->save();
        //BACKUP of POS sales: Making pos sale backup to Salebackup table 
        $saleCartBackup = [];
        foreach(Duesale::where('dueordersale_id',
        $order->id)->get() as $saleCart){
            $cart_backup = [
                'product_id' => $saleCart->product_id,
                'admin_id' => $saleCart->admin_id,
                'dueordersale_id' => $saleCart->dueordersale_id,
                'product_name' => $saleCart->product_name,
                'product_quantity' => $saleCart->product_quantity,
                'unit_price' => $saleCart->unit_price,
                'production_food_cost' => $saleCart->production_food_cost,
                'order_cancel' => 1,
                'order_tbl_no' => $saleCart->order_tbl_no,
            ];            
            $saleCartBackup[] = $cart_backup;
        } 
        \DB::table('salebackups')->insert($saleCartBackup);
        //Now Deleting record from pos sale table in order to free up space to pos sale table
        foreach(Duesale::where('dueordersale_id',
        $order->id)->get() as $saleCart){
            $saleCart->delete();
        }

        return response()->json(['success' => 'Data is updated successfully']);  
         
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

    public function search(Request $request){
        $search = trim($request->search); // getting the search key 
        // relationship with client
       // search criteria.      
        $orders = Dueordersale::orWhere('order_number', 'like', '%'.$search.'%') 
                ->orWhere('payment_date', 'like', '%'. ($this->validateDateTime($search) ? Carbon::createFromFormat('d-m-Y H:i:s', $search)->format('Y-m-d H:i:s') : $search).'%')   
                ->orWhere('payment_date', 'like', '%'. ($this->validateDate($search) ? Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d') : $search).'%') 
                ->orWhere('order_date', 'like', '%'. ($this->validateDateTime($search) ? Carbon::createFromFormat('d-m-Y H:i:s', $search)->format('Y-m-d H:i:s') : $search).'%')   
                ->orWhere('order_date', 'like', '%'. ($this->validateDate($search) ? Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d') : $search).'%')   
                ->orWhere('receive_total', 'like', '%'.$search.'%') 
                ->orWhere('due_payable', 'like', '%'.$search.'%')
                ->orWhereHas('client', function ($query) use($search) {
                    $query->where('mobile', '=', $search);})
                ->orWhere('status', 'like', '%'.$search.'%')    
                ->paginate(10);  
         // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'KOT Due Orders', 'subTitle' => 'List of Search Orders' ]);
        return view('admin.sales.due.orderlists', compact('orders'));
    }

    public function paymentindex($id){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Due Sell KOT System', 'subTitle' => 'Selected products for sales and make order placement' ]);
        return view('admin.sales.due.payment.index')->with('order_id', $id);
    }

    public function searchDueCart(Request $request){
        $search = trim($request->search); // getting the search key        
       // search criteria.      
        $order = Dueordersale::orWhere('order_number', 'like', '%'.$search.'%')
                ->orWhereHas('client', function ($query) use($search) {
                $query->where('mobile', '=', $search);})->first();
        if($order){
            return redirect()->route('admin.due.sales.paymentindex', $order->id); 
        }
        else{
            return $this->responseRedirectBack(' Sorry, the due order no is not found!' ,'error', false, false); 
        }
    }

    public function getMobileNo(Request $request){

        $search = $request->search;
       
        $customers = Client::orderby('name','asc')
                    ->select('name','mobile')
                    ->where('mobile', 'like', '%' .$search . '%')                    
                    ->limit(5)
                    ->get();        

        $response = array();
        foreach($customers as $customer){            
            $response[] = array( "value" => $customer->name, "label" => $customer->mobile
            );            
        }

        return response()->json($response); 
    }

    public function addCustomerInfo(Request $request){
        
        $mobile = $request->mobile;

        $customer = Client::select('name','mobile','address','notes','total_points')                    
                    ->where('mobile', 'like', '%' .$mobile . '%')
                    ->get();

        return response()->json($customer); 

    }

    public function discountSlab(Request $request){ 
        $directorId = $request->directorId;
        $orderTotal = $request->orderTotal;
        $discount = $request->discount;
        // getting the reference director using director id. 
        $director = Director::where('id', $request->directorId)->first();
        $discount_upper_limit = $director->discount_upper_limit;
        $discount_slab_percentage = $director->discount_slab_percentage;
        $discount_limit = $orderTotal * ($discount_slab_percentage/100); //percentage limit
        return json_encode(['status' => 'success', 'discountLimit' => $discount_limit, 'discount' => $discount, 'discountUpperLimit' => $discount_upper_limit ] );
    }

    public function gpStarDiscount(Request $request){
        $gpstarId = $request->gpstarId;
        $gpstar_discount = Gpstardiscount::where('id', $request->gpstarId)->first();
        $discount_percent = $gpstar_discount->discount_percent;
        $discount_upper_limit = $gpstar_discount->discount_upper_limit;
        return json_encode(['status' => 'success', 'discountPercent' => $discount_percent, 'discountUpperLimit' => $discount_upper_limit ] );
    }

    public function cardDiscount(Request $request){
        $card_bank = $request->cardBank;
        $card = Paymentgw::where('bank_type', 'card')->where('bank_name', $card_bank)->first();
        $card_discount = $card->discount_percent;
        $discount_upper_limit = $card->discount_upper_limit;
        return response()->json(['cardDiscount' => $card_discount, 'upperLimit' => $discount_upper_limit]);
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
        * storing all the payment details to db table ordersalepayments and calculating cash_pay, card_pay or mobile_pay.
        */
        $order_payments = '';
        // As each order might have single card discount or mobile discount, so we store it directly.        
        $card_discount = 0;
        $fraction_discount = 0; 
        //calculating cash_pay, card_pay & mobile_banking_pay for the current order.       
        $cash_pay = 0; $card_pay = 0; $mobile_banking_pay = 0; $customer_due =0.0;
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
            $ordersalepayment->dueordersale_id = $single_payment_details['saleOrderId'];
            $ordersalepayment->payment_method = $single_payment_details['paymentMethod'];
            $ordersalepayment->bank_name = $single_payment_details['bankName'];
            $ordersalepayment->card_discount = $single_payment_details['cardDiscount'];
            $ordersalepayment->customer_paid_amount = $single_payment_details['customerPaid'];
            $ordersalepayment->cash_exchange = $single_payment_details['due'] < 0 ? $single_payment_details['due'] : 0;
            $ordersalepayment->customer_due = $single_payment_details['due'] > 0 ? $single_payment_details['due'] : 0;
            //store_paidamount = Customer paid + due; as due can be negative value that means 500 + due(-50) = 450 taka will be stored.
            $ordersalepayment->store_paidamount = $single_payment_details['customerPaid'] + ($single_payment_details['due'] < 0 ? $single_payment_details['due'] : 0);            
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
            //C-1: when due >= 0 with multimode payments.
            if($single_payment_details['due'] >= 0) {
                if($single_payment_details['paymentMethod'] == 'cash'){
                    $cash_pay += $single_payment_details['customerPaid'];
                }elseif($single_payment_details['paymentMethod'] == 'card'){
                    $card_pay += $single_payment_details['customerPaid'];
                }elseif($single_payment_details['paymentMethod'] == 'mobile'){
                    $mobile_banking_pay += $single_payment_details['customerPaid'];
                }
            }
            //C-2: when due is less than 0 (due < 0): customer has received cash exchanged.
            if($single_payment_details['due'] < 0) {
                // Calculating actual paid amount paid by the customer. 500 -50
                $actual_paid_amount = $single_payment_details['customerPaid'] + $single_payment_details['due'];

                if($single_payment_details['paymentMethod'] == 'cash'){
                    $cash_pay += $actual_paid_amount;
                }elseif($single_payment_details['paymentMethod'] == 'card'){
                    $card_pay += $actual_paid_amount;
                }elseif($single_payment_details['paymentMethod'] == 'mobile'){
                    $mobile_banking_pay += $actual_paid_amount;
                }
            }   
            $customer_due =  $single_payment_details['due'];     
            
        }//end of foreach loop.

        //Order Update: Discount, reward points discount, Payment Details, Customer Points
        $order = Dueordersale::where('id', $request->order_id)->first();
        $order->admin_id = auth()->user()->id;     
        //$order->order_number = $ord_id; 
        $order->discount = $order->discount ? $order->discount : $request->order_discount; //referene discount.
        $order->reward_discount = $order->reward_discount ? $order->reward_discount : $request->reward_discount;
        $order->director_id = $order->director_id ? $order->director_id : $request->order_discount_reference;        
        $order->payment_date = \Carbon\Carbon::now()->toDateTimeString(); 
        $order->payment_method = implode(',', $request->payment_method); // making array to string before saving to database.
        $order->cash_pay = $order->cash_pay ?  $order->cash_pay + $cash_pay : $cash_pay;
        $order->card_pay = $order->card_pay ? $order->card_pay + $card_pay : $card_pay;
        $order->mobile_banking_pay = $order->mobile_banking_pay ? $order->mobile_banking_pay + $mobile_banking_pay : $mobile_banking_pay; 
        $order->card_discount = $order->card_discount ? $order->card_discount : $card_discount; //either card or mobile bank discount will store.
        $order->order_tableNo = $request->customer_due ? $order->order_tableNo : NULL;//$request->order_tableNo; as that table no should be free to feed another customer.   
        $order->status = $customer_due > 0 ? 'receive' : 'delivered';
        $order->fraction_discount = $fraction_discount;
        $order->gpstarmobile_no = $request->gpstarmobile;
        $order->gpstar_discount = $order->gpstar_discount ? $order->gpstar_discount : $request->gpstar_discount;

        $order->order_total = $order->order_total ?? $request->subtotal + ($request->subtotal * (config('settings.tax_percentage')/100));
        //calculating vat 
        $order->vat = config('settings.tax_percentage') ? ($request->subtotal * (config('settings.tax_percentage')/100)) : 0;
        //calculating order grand total + tax, if exists        
        $order_total = $order->order_total ? $order->order_total - $order->receive_total : ($request->subtotal + ($request->subtotal * (config('settings.tax_percentage')/100)) - $order->receive_total);
        //substracting director reference discount        
        $order_discount_total = $order->discount ? $order_total : ($request->order_discount ?  $order_total - $request->order_discount : $order_total);
        //substracting reward point discount
        $order_grand_total = $order->reward_discount ? $order_discount_total :($request->reward_discount ? $order_discount_total - $request->reward_discount : $order_discount_total);  
        //substracting card discount
        $order_grand_total = $order->card_discount ? $order_grand_total :($card_discount ? $order_grand_total - $card_discount : $order_grand_total);  
        //substracting fraction discount
        $order_grand_total = $order->fraction_discount ? $order_grand_total :($fraction_discount ? $order_grand_total - $fraction_discount : $order_grand_total); 
        //substracting gpstar discount
        $order_grand_total = $order->gpstar_discount ? $order_grand_total :($request->gpstar_discount ? $order_grand_total - $request->gpstar_discount : $order_grand_total);

        //updating receive total
        $order->receive_total += ($order_grand_total - ($customer_due > 0 ? $customer_due : 0 ));
        $order->due_payable = $customer_due > 0 ? $customer_due : 0;

        //grand total after substracting all the discount options      
        $order->grand_total = $order->receive_total;
        //$order->due_status =  (float)$order->grand_total - (float)$order->receive_total ?? 1;

        // if customer data is not available we will not create the customer details.
        if($request->customer_mobile){    
            //checking if client not exists, we create client here to store client information. 
            $client =  Client::where('mobile', $request->customer_mobile)->first();
            // customer points calculation.        
            $client->total_points += $order->grand_total / (config('settings.money_to_point'));
            //if reward discount is used, we will set client total_points to zero.
            $client->total_points = $request->reward_discount ? 0 : $client->total_points;
            $client->save();            
        }
        //updating the order
        $order->save();

        if(!$order->due_payable){
            //Inventory Management: We will deduct product quantity and product total cost using product id from ingredient stock. 

            //finding the cart using order id... it may return many sale carts for pos system
            foreach($order->duesales as $cart){
                //getting product quantity that user has purchased.
                $cart_product_quantity = $cart->product_quantity;
                //using product id finding the recipe and then finding the ingredients of the recipe
                foreach(Recipe::where('product_id', $cart->product_id)->first()->recipeingredients as $recipeingredient){
                    //getting the ingredient.
                    $ingredient = $recipeingredient->ingredient;                   
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

            //creating kot/pos ordersales new record as no dues for that order and we just copy dueordersales to ordersales.
            // finding last order id ordersales.            
            if(!Ordersale::orderBy('id', 'desc')->first()){
                $ord_id = 0;
            }
            else{
                $ord_id = Ordersale::orderBy('id', 'desc')->first()->id; 
            }   
            $ord_id = '#'.(10000 + ($ord_id + 1));

            $orderSale                  = new Ordersale(); // we use order_id as online transaction id.
            $orderSale->admin_id        = auth()->user()->id;  
            $orderSale->client_id       = $order->client_id; 
            $orderSale->director_id     = $order->director_id;  
            $orderSale->order_number    = $ord_id; 
            $orderSale->grand_total     = $order->grand_total;
            $orderSale->vat             = $order->vat;
            $orderSale->order_date      = \Carbon\Carbon::now()->toDateTimeString();
            $orderSale->status          = 'delivered';
            $orderSale->discount        = $order->discount;  // getting data from dueordersale.
            $orderSale->reward_discount = $order->reward_discount;            
            $orderSale->payment_method  = $order->payment_method;
            $orderSale->cash_pay        = $order->cash_pay + $order->booked_money; // advance payment + cash payment
            $orderSale->card_pay        = $order->card_pay;
            $orderSale->mobile_banking_pay = $order->mobile_banking_pay;
            $orderSale->card_discount   = $order->card_discount;    //either card or mobile bank discount will store.         
            $orderSale->fraction_discount = $order->fraction_discount;
            $orderSale->gpstarmobile_no = $order->gpstarmobile_no;
            $orderSale->gpstar_discount = $order->gpstar_discount;
            $orderSale->hide_order_total = config('settings.hide_order_amount') ?? 0;   
            $orderSale->save();

            //BACKUP of POS sales: Making pos sale backup to Salebackup table 
            $saleCartBackup = [];        
            foreach(Duesale::where('dueordersale_id',
            $order->id)->get() as $saleCart){
                $cart_backup = [
                    'product_id' => $saleCart->product_id,
                    'admin_id' => $saleCart->admin_id,
                    'ordersale_id' =>$orderSale->id, // inserting newly created Ordersales record id for all duesale purchased foods.
                    'dueordersale_id' => $saleCart->dueordersale_id,
                    'product_name' => $saleCart->product_name,
                    'product_quantity' => $saleCart->product_quantity,
                    'unit_price' => $saleCart->unit_price,
                    'production_food_cost' => $saleCart->production_food_cost == NULL ? Recipe::where('product_id',$saleCart->product_id)->first()->production_food_cost : 0,
                    'order_cancel' => $saleCart->order_cancel,
                    'order_tbl_no' => $saleCart->order_tbl_no,
                    'created_at' => $saleCart->created_at,
                    'updated_at' => $saleCart->updated_at,
                ];            
                $saleCartBackup[] = $cart_backup;
            } 
            \DB::table('salebackups')->insert($saleCartBackup);
            //Now updating ordersalepayments and set ordersale_id for dueordersales when has no dues
            foreach(Ordersalepayment::where('dueordersale_id', $order->dueordersale_id)->get() as $single_payment){
                $single_payment->ordersale_id = $orderSale->id;
                $single_payment->save();
            }
            //Now Deleting record from pos sale table in order to free up space to pos sale table
            foreach(Duesale::where('dueordersale_id',
                $order->id)->get() as $saleCart){
                    $saleCart->delete();
                }

        }              

        
        
        

        // setting flash message using trait
        $this->setFlashMessage(' Order is updated successfully', 'success');    
        $this->showFlashMessages();
        return redirect()->route('admin.due.sales.paymentindex', $order->id);   
       
    }

}
