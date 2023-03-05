<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Ordersale;
use App\Traits\FlashMessages; 
use Carbon\Carbon;
use DateTime;
use Auth;
use App\Models\Recipe;
use App\Models\Unit;


class PosOrderController extends Controller
{
    use FlashMessages;

    public function index(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'KOT Lists', 'subTitle' => 'List of all KOT Orders' ]);
        $orders = Ordersale::orderBy('created_at', 'desc')->paginate(28);
        return view('admin.sales.orders.index', compact('orders'));
    }

    public function edit($id){
        // Attaching pagetitle and subtitle to view.          
        $order = Ordersale::where('id', $id)->first();
        view()->share(['pageTitle' => 'POS Orders', 'subTitle' => 'KOT No: '.$order->order_number ]);
        return view('admin.sales.orders.edit', compact('order'));
    }

    // public function update(Request $request){

    //     $this->validate($request,[  
    //         'order_tableNo'    => 'required|string|max:10',
    //         'status'           => 'nullable|string|max:40',             
                        
    //     ]);
        
    //     $order = Ordersale::where('id', $request->id)->first();       
    //     $order->order_tableNo = $request->order_tableNo;      
    //     $order->status = $request->status;
    //     $order->save(); 

    //     // setting flash message using trait
    //     $this->setFlashMessage(' Order is updated successfully', 'success');    
    //     $this->showFlashMessages();
    //     return redirect()->route('admin.pos.orders.index');
    // }

    public function search(Request $request){
        $search = trim($request->search); // getting the search key
        
       // search criteria.      
        $orders = Ordersale::orWhere('order_number', 'like', '%'.$search.'%') 
        ->orWhere('order_tableNo', 'like', '%'.$search.'%')      
        ->orWhere('order_date', 'like', '%'. ($this->validateDateTime($search) ? Carbon::createFromFormat('d-m-Y H:i:s', $search)->format('Y-m-d H:i:s') : $search).'%')   
        ->orWhere('order_date', 'like', '%'. ($this->validateDate($search) ? Carbon::createFromFormat('d-m-Y', $search)->format('Y-m-d') : $search).'%')   
        ->orWhere('grand_total', 'like', '%'.$search.'%') 
        ->orWhere('status', 'like', '%'.$search.'%')     
        ->orWhere('payment_method', 'like', '%'.$search.'%')->paginate(10); 
         
         // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'POS Orders', 'subTitle' => 'List of Search Orders' ]);
        return view('admin.sales.orders.index', compact('orders'));
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

    /*
    * Ajax request
    */
    public function orderStatusUpdate(Request $request){
        $order = Ordersale::find($request->id);
        $order->status = $request->status;
        if($request->status == 'cancel'){
            $order->order_tableNo = NULL;
        }
        $order->save();
        //BACKUP of POS sales: Making pos sale backup to Salebackup table 
        $saleCartBackup = [];
        foreach(Sale::where('ordersale_id',
        $order->id)->get() as $saleCart){
            $cart_backup = [
                'product_id' => $saleCart->product_id,
                'admin_id' => $saleCart->admin_id,
                'ordersale_id' => $saleCart->ordersale_id,
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
        foreach(Sale::where('ordersale_id',
        $order->id)->get() as $saleCart){
            $saleCart->delete();
        }

        return response()->json(['success' => 'Data is updated successfully']);  
         
    }

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

    public function addToSales(Request $request){
        /*commented out only for readymade food sale */
        // // checking the food is added to the recipe
        // if(!Recipe::where('product_id', $request->foodId)->first()){
        //   return json_encode([ 'status' => 'info', 'message' => "Please add '". $request->foodName ."' food recipe before you sale." ]);           
        // }// redipe is added but recipe ingredients is not added for the food
        // elseif(!Recipe::where('product_id', $request->foodId)->first()->recipeingredients->count()){          
        //     return json_encode([ 'status' => 'info', 'message' => "Please add '". $request->foodName ."' food recipe ingredients before you  sale." ]);
        // }

        $product = Product::find($request->foodId);
        //checking discount price is enabled for this product
        if($product->discount_price){
            $sale_price = $product->discount_price;
        }else{
            $sale_price = $product->price;
        }

        //checking the product whether it is already added to the sale cart, if so we sent the message this product is already added to the cart.
        $sale = Sale::where('product_id', $request->foodId)
                    ->where('order_tbl_no', $request->orderTableNo ) 
                    ->where('ordersale_id', $request->orderId )                   
                    ->first();

        if(!is_null($sale)){
            return json_encode([ 'status' => 'info', 'message' => "This food is already added to the cart."  ]);
        }
        else{
            // adding new item to the sale cart
            $sale = new Sale();
            $sale->admin_id = Auth::id(); 
            $sale->product_id = $request->foodId;
            $sale->product_name = $request->foodName;
            $sale->order_tbl_no = $request->orderTableNo;
            $sale->ordersale_id = $request->orderId;
            $sale->unit_price = $sale_price;
            $sale->product_quantity = 1.00;
            $sale->save();

            // if($request->ajax()){
            //     return json_encode([ 'status' => 'ok', 'a' => $sale->product_name, 'b' => $sale->unit_price, 'c' => $sale->product_quantity, 
            //     'd' => $sale->id, 'e' => $request->orderId, 'f'=> $sale_price ]);
            // }

            return json_encode([ 'status' => 'success', 'foodname' => $sale->product_name , 'price' => $sale->unit_price, 'qty' => $sale->product_quantity, 'id' => $sale->id , 'sub_total' => $this->calculateSubtotal($request->orderId) ]);
            
        }          
        
    }

    public function calculateSubtotal($orderId){
        // calculating subtotal
        $total_taka=0.0;
        foreach(Sale::where('admin_id', Auth::id())->where('ordersale_id',$orderId)->get() as $sale){      
          
            if($sale->product->discount_price){
                    $total_taka += $sale->product->discount_price * $sale->product_quantity;
            }else{
                    $total_taka += $sale->product->price * $sale->product_quantity;
            }         
        }
        return $total_taka;
    }

    public function destroy(Request $request)
    {
        $sale = Sale::find($request->sale_id);
        if(!is_null($sale)){            
            $sale->delete();
            return json_encode([ 'status' => 'success', 'message' => 'Item is deleted', 'sub_total' => $this->calculateSubtotal($sale->ordersale_id) ]); 
        }
        return json_encode(['status' => 'error', 'message' => 'An error is occurred while deleting the cart' ]);
    }  

    public function update(Request $request)
    {  
        $id = $request->sale_id;                
        $sale = Sale::find($id);//primary key id
        $sale->product_quantity = $request->product_quantity;           
        $sale->save();
        
        if($sale->product->discount_price){
                $total_unit_price = $sale->product->discount_price * $sale->product_quantity;
        }else{
                $total_unit_price = $sale->product->price * $sale->product_quantity;
        }
         
        return json_encode([ 'status' => 'success', 'total_unit_price' => $total_unit_price, 'sub_total' => $this->calculateSubtotal($sale->ordersale_id) ]);      
    }



      


    


}
