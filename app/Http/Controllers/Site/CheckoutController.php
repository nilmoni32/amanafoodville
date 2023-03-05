<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\Cart;
use App\Models\Order;
use App\Models\ProductAttribute;
use App\Models\District;
use Session;
use Auth;
use App\Events\OrderPlaced;
use App\Library\SslCommerz\SslCommerzNotification;

class CheckoutController extends Controller
{
    public function getCheckout(){
        return view('site.pages.checkout');
    }

    public function getZones($id){    
        $zones = Zone::where('district_id',$id)->where('status', 1 )->pluck("name","id");
        return json_encode($zones);
    }

    public function getUserAddress(){     
        // when user checked user default address
        return json_encode([ 'status' => 'success', 'address' => auth()->user()->address]);
    }

    public function placeOrder(Request $request){ 
        
    $this->validate($request,[  
        'name' => 'required|string|max:40',
        'email' => 'nullable|string|email|max:100,', 
        'phone_no' =>  'required|regex:/(01)[3-9]{1}(\d){8}/|max:13',       
        'address_txt' =>  'required|string|max:191', 
        'district' => 'required|string',
        'zone' => 'required|string',
    ]);
    
    $shipping_cost = (float)config('settings.delivery_charge');    
    $sub_total = Cart::calculateSubtotal();
    $vat = $sub_total * (config('settings.tax_percentage')/100);
    $grand_total = $sub_total + $shipping_cost + $vat;

    // finding last order id: we use it for customer order id (customized) for billing purpose
    // it will be false only for the first record.
    if(!Order::orderBy('id', 'desc')->first()){
        $ord_id = 0;
    }
    else{
        $ord_id = Order::orderBy('id', 'desc')->first()->id; 
    }   
    $ord_id = '#'.(100000 + ($ord_id + 1));

    $order = new Order(); // we use order_id as online transaction id.
    $order->user_id = auth()->user()->id;     
    $order->order_number = $ord_id; 
    $order->payment_method = 'Cash';
    $order->bank_tran_id = 'N/A';
    $order->status = 'pending';
    $order->payment_status = 0;
    $order->grand_total = $grand_total;
    $order->item_count = Cart::totalItems();
    $order->name = $request->name;
    $order->email = $request->email;
    $order->phone_no = $request->phone_no;
    $order->address = $request->address_txt;
    $order->district = District::where('id', $request->district)->first()->name;
    $order->zone = Zone::where('id',  $request->zone)->first()->name;
    $order->order_date = \Carbon\Carbon::now()->toDateTimeString();
    $order->delivery_date = $request->delivery_timings;
    $order->save();
    // when order is placed we set order_id to cart for that cart and set cart ip_address to null as it is used
    // for guest only.
    foreach(Cart::totalCarts() as $cart){
        $cart->order_id = $order->id;
        $cart->ip_address = NULL;       
        $cart->save();
    }

    // An event is triggered to notify backend user for an new order placement
    event(new OrderPlaced($order->order_number));
   
    return redirect()->route('checkout.payment', $order->id);

    }

    public function checkoutPayment($id){
        //getting the order for the respective user.
        $order = Order::where('id', $id)->first();        
        return view('site.pages.payment', compact('order'));
    }

    public function cancelOrder($id){
        //getting the order for the respective user.
        $order = Order::where('id', $id)->first();
        $order->status = "cancel";
        $order->payment_method = 'None';
        $order->error = 'Cancelled by user';
        $order->save();
        // when order is canceled by user after checkout, we need to set order_cancel to 1 in the cart table for that cart 
        // we need this for reporting purpose.       
        foreach(Cart::where('user_id', Auth::id())->where('order_id', $order->id)->get() as $cart){
            $cart->order_cancel = 1;
            $cart->save();
        }
        
        // // An event is triggered to notify backend user for an new order placement
        // event(new OrderPlaced($order->order_number));
        
        if(session()->has('error') && session()->get('error') !== ''){
            session()->flash('error', '');
        }
        session()->flash('error', 'The Order has been Canceled by user.');
        return view('site.pages.paynotify', compact('order'));
    }

    public function cashOrder($id){
         //getting the order for the respective user.
        $order = Order::where('id', $id)->first();        
        $order->payment_method = 'Cash';
        $order->bank_tran_id = 'N/A';
        $order->save();

        // // An event is triggered to notify backend user for an new order placement
        // event(new OrderPlaced($order->order_number));

        if(session()->has('success') && session()->get('success') !== ''){
            session()->flash('success', '');
        }
        session()->flash('success', 'Thank you for your recent order. Your shipment is on its way!');
        return view('site.pages.paynotify', compact('order'));
    }   

   

    

    
}
