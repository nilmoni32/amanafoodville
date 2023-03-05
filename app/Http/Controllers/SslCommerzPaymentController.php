<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\Cart;
use App\Models\Cartbackup;
use App\Models\Order;
use App\Models\ProductAttribute;
use App\Models\District;
use Session;
use Auth;
use App\Events\OrderPlaced;
use App\Library\SslCommerz\SslCommerzNotification;

class SslCommerzPaymentController extends Controller
{

    // public function exampleEasyCheckout()
    // {
    //     return view('exampleEasycheckout');
    // }

    // public function exampleHostedCheckout()
    // {
    //     return view('exampleHosted');
    // }

    public function index(Request $request)
    {
        # Here you have to receive all the order data to initate the payment.
        # Let's say, your oder transaction informations are saving in a table called "orders"
        # In "orders" table, order unique identity is "transaction_id". "status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.
       
        $order = Order::find($request->id);

        $post_data = array();
        $post_data['total_amount'] = $order->grand_total; # You cant not pay less than 10
        $post_data['currency'] = "BDT";
        $post_data['tran_id'] = $order->id; // tran_id must be unique

        # CUSTOMER INFORMATION
        $post_data['cus_name'] = $order->name;
        $post_data['cus_email'] = $order->email ?? config('settings.default_email_address');
        $post_data['cus_add1'] = $order->address;
        $post_data['cus_add2'] = "";
        $post_data['cus_city'] = "";
        $post_data['cus_state'] = "";
        $post_data['cus_postcode'] = "";
        $post_data['cus_country'] = "Bangladesh";
        $post_data['cus_phone'] = $order->phone_no;
        $post_data['cus_fax'] = "";

        # SHIPMENT INFORMATION
        $post_data['ship_name'] = "testamanamkhy";
        $post_data['ship_add1'] = "";
        $post_data['ship_add2'] = "";
        $post_data['ship_city'] = "";
        $post_data['ship_state'] = "";
        $post_data['ship_postcode'] = "";
        $post_data['ship_phone'] = "";
        $post_data['ship_country'] = "Bangladesh";

        $post_data['shipping_method'] = "NO";
        $post_data['product_name'] = "TestFood";
        $post_data['product_category'] = "Foods";
        $post_data['product_profile'] = "restaurant foods";

        # OPTIONAL PARAMETERS
        $post_data['value_a'] = "ref001";
        $post_data['value_b'] = "ref002";
        $post_data['value_c'] = "ref003";
        $post_data['value_d'] = "ref004";
        

        $sslc = new SslCommerzNotification();
        # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
        $payment_options = $sslc->makePayment($post_data, 'hosted');

        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        } 

    }

    // public function payViaAjax(Request $request)
    // {

    //     # Here you have to receive all the order data to initate the payment.
    //     # Lets your oder trnsaction informations are saving in a table called "orders"
    //     # In orders table order uniq identity is "transaction_id","status" field contain status of the transaction, "amount" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.

    //     $post_data = array();
    //     $post_data['total_amount'] = '10'; # You cant not pay less than 10
    //     $post_data['currency'] = "BDT";
    //     $post_data['tran_id'] = uniqid(); // tran_id must be unique

    //     # CUSTOMER INFORMATION
    //     $post_data['cus_name'] = 'Customer Name';
    //     $post_data['cus_email'] = 'customer@mail.com';
    //     $post_data['cus_add1'] = 'Customer Address';
    //     $post_data['cus_add2'] = "";
    //     $post_data['cus_city'] = "";
    //     $post_data['cus_state'] = "";
    //     $post_data['cus_postcode'] = "";
    //     $post_data['cus_country'] = "Bangladesh";
    //     $post_data['cus_phone'] = '8801XXXXXXXXX';
    //     $post_data['cus_fax'] = "";

    //     # SHIPMENT INFORMATION
    //     $post_data['ship_name'] = "Store Test";
    //     $post_data['ship_add1'] = "Dhaka";
    //     $post_data['ship_add2'] = "Dhaka";
    //     $post_data['ship_city'] = "Dhaka";
    //     $post_data['ship_state'] = "Dhaka";
    //     $post_data['ship_postcode'] = "1000";
    //     $post_data['ship_phone'] = "";
    //     $post_data['ship_country'] = "Bangladesh";

    //     $post_data['shipping_method'] = "NO";
    //     $post_data['product_name'] = "Computer";
    //     $post_data['product_category'] = "Goods";
    //     $post_data['product_profile'] = "physical-goods";

    //     # OPTIONAL PARAMETERS
    //     $post_data['value_a'] = "ref001";
    //     $post_data['value_b'] = "ref002";
    //     $post_data['value_c'] = "ref003";
    //     $post_data['value_d'] = "ref004";


    //     #Before  going to initiate the payment order status need to update as Pending.
    //     $update_product = DB::table('orders')
    //         ->where('transaction_id', $post_data['tran_id'])
    //         ->updateOrInsert([
    //             'name' => $post_data['cus_name'],
    //             'email' => $post_data['cus_email'],
    //             'phone' => $post_data['cus_phone'],
    //             'amount' => $post_data['total_amount'],
    //             'status' => 'Pending',
    //             'address' => $post_data['cus_add1'],
    //             'transaction_id' => $post_data['tran_id'],
    //             'currency' => $post_data['currency']
    //         ]);

    //     $sslc = new SslCommerzNotification();
    //     # initiate(Transaction Data , false: Redirect to SSLCOMMERZ gateway/ true: Show all the Payement gateway here )
    //     $payment_options = $sslc->makePayment($post_data, 'checkout', 'json');

    //     if (!is_array($payment_options)) {
    //         print_r($payment_options);
    //         $payment_options = array();
    //     }

    // }

    public function success(Request $request)
    {
        //dd($request->all());
        $tran_id = $request->input('tran_id');
        $amount = $request->input('amount');
        $currency = $request->input('currency');
            
        $sslc = new SslCommerzNotification();
    
        #Check order status in order tabel against the transaction id
        $order = Order::find($tran_id);  
    
        if ($order->status == 'pending') {
            $validation = $sslc->orderValidate($tran_id, $amount, $currency, $request->all());

            if ($validation == TRUE) {
                /*
                That means IPN did not work or IPN URL was not set in your merchant panel. Here you need to update order status
                in order table as Processing or Complete.
                Here you can also sent sms or email for successfull transaction to customer
                */
                $order->payment_status = 1; // payment = 1 means paid.
                $order->payment_method = $request->card_type; // specify the card
            
                $order->tran_date = $request->tran_date;
                $order->tran_id = $tran_id;
                $order->amount = $request->amount;
                $order->store_amount = $request->store_amount; 
                $order->bank_tran_id = $request->bank_tran_id;
                $order->currency_type = $request->currency_type;
                $order->currency_amount = $request->currency_amount;
                $order->card_no = $request->card_no;
                $order->card_brand = $request->card_brand;
                $order->card_issuer = $request->card_issuer;

                $order->save();
                if(session()->has('success') && session()->get('success') !== ''){
                    session()->flash('success', '');
                }
                session()->flash('success', 'The payment corresponding to the order has received and your shipment is on its way!');       
                return view('site.pages.paynotify', compact('order'));
                } else {
                    /*
                    That means IPN did not work or IPN URL was not set in your merchant panel and Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $order->status = 'failed';
                    $order->payment_method = 'failed'; // specify the card method failed
                    $order->save();
                    if(session()->has('error') && session()->get('error') !== ''){
                        session()->flash('error', '');
                    }
                    session()->flash('error', 'Sorry!! the payment corresponding to the order has failed.');
                    return view('site.pages.paynotify', compact('order'));
                }
        }else {
            #That means something wrong happened. You can redirect customer to your product page.
            //echo "Invalid Transaction";
            session()->flash('error', 'Invalid Transaction');       
            return redirect()->route('products.index');
        }


    }

    public function fail(Request $request)
    {
        $tran_id = $request->input('tran_id');

        $order = Order::find($tran_id);

        if($request->status == "FAILED"){
            $order->status = 'failed';
            $order->payment_method = 'none';
            $order->error = $request->error; 

            $order->tran_date = $request->tran_date;
            $order->tran_id = $tran_id;
            $order->amount = $request->amount;            
            $order->bank_tran_id = $request->bank_tran_id;
            $order->currency_type = $request->currency_type;
            $order->currency_amount = $request->currency_amount;
            $order->card_no = $request->card_no;
            $order->card_brand = $request->card_brand;
            $order->card_issuer = $request->card_issuer;

            $order->save(); 
            // // An event is triggered to notify backend user for an new order placement
            // event(new OrderPlaced($order->order_number));             
        }
        if(session()->has('error') && session()->get('error') !== ''){
            session()->flash('error', '');
        }
        session()->flash('error', 'Sorry!! the payment corresponding to the order has failed.');
        return view('site.pages.paynotify', compact('order'));
    }

    public function cancel(Request $request)
    {
        $tran_id = $request->input('tran_id');
        $order = Order::find($tran_id);

        if($request->status == "CANCELLED"){
            $order->status = 'cancel';
            $order->payment_method = 'none'; 
            $order->error = $request->error; 

            $order->tran_date = $request->tran_date;
            $order->tran_id = $tran_id;
            $order->amount = $request->amount;
            $order->currency_type = $request->currency_type;
            $order->currency_amount = $request->currency_amount;

            $order->save();
            // // An event is triggered to notify backend user for an new order placement
            // event(new OrderPlaced($order->order_number));

            //BACKUP of e-commerce cart: Making ecommerce cart backup to cartbackups            
            // when order is canceled by user , we need to set order_cancel to 1 for reporting purpose.   
            $ecommCartBackup = [];
            foreach(Cart::where('order_id',
            $order->id)->get() as $cart){
                $cart_backup = [
                    'product_id' => $cart->product_id,
                    'user_id' => $cart->user_id,
                    'order_id' => $cart->order_id,
                    'product_attribute_id' => $cart->product_attribute_id,
                    'ip_address' => $cart->ip_address,
                    'product_quantity' => $cart->product_quantity,
                    'has_attribute' => $cart->has_attribute,
                    'unit_price' => $cart->unit_price,                    
                    'order_cancel' => 1, 
                    'production_food_cost' => $cart->production_food_cost,
                    'created_at' => $cart->created_at,
                    'updated_at' => $cart->updated_at,
                ];            
                $ecommCartBackup[] = $cart_backup;
            } 
            \DB::table('cartbackups')->insert($ecommCartBackup);
            //Now Deleting record from e-commerce cart table in order to free up space.
            foreach(Cart::where('order_id',
            $order->id)->get() as $cart){
                $cart->delete();
            }
        }
        if(session()->has('error') && session()->get('error') !== ''){
            session()->flash('error', '');
        }
        session()->flash('error', 'The payment corresponding to the order has been canceled.');
        return view('site.pages.paynotify', compact('order'));


    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $order = Order::find($tran_id);

            if ($order->status == 'pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($tran_id, $order->amount, $order->currency_type, $request->all());
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as Processing or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $order->payment_status = 1; // payment = 1 means paid.
                    $order->payment_method = $request->card_type; // specify the card
                
                    $order->tran_date = $request->tran_date;
                    $order->tran_id = $tran_id;
                    $order->amount = $request->amount;
                    $order->store_amount = $request->store_amount; 
                    $order->bank_tran_id = $request->bank_tran_id;
                    $order->currency_type = $request->currency_type;
                    $order->currency_amount = $request->currency_amount;
                    $order->card_no = $request->card_no;
                    $order->card_brand = $request->card_brand;
                    $order->card_issuer = $request->card_issuer;

                    $order->save();
                    if(session()->has('success') && session()->get('success') !== ''){
                        session()->flash('success', '');
                    }
                    session()->flash('success', 'The payment corresponding to the order has received and your shipment is on its way!');       
                    return view('site.pages.paynotify', compact('order'));
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $order->status = 'failed';
                    $order->payment_method = 'failed'; // specify the card method failed
                    $order->save();
                    if(session()->has('error') && session()->get('error') !== ''){
                        session()->flash('error', '');
                    }
                    session()->flash('error', 'Sorry!! the payment corresponding to the order has failed.');
                    return view('site.pages.paynotify', compact('order'));
                }

            } else {
                #That means something wrong happened. You can redirect customer to your product page.
                session()->flash('error', 'Invalid Transaction');       
                return redirect()->route('products.index');
            }
        } else {
            session()->flash('error', 'Invalid Transaction');       
            return redirect()->route('products.index');
        }
    }

}
