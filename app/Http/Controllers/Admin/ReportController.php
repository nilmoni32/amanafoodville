<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Product;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DailyExport;
use App\Exports\DailyTotalExport;
use App\Exports\MonthlyTotalExport;
use App\Exports\YearlyTotalExport;
use App\Exports\ItemBasedExport;
use App\Exports\SingleItemExport;
use Carbon\Carbon;
use App\Http\Controllers\BaseController;

class ReportController extends BaseController
{ 
    /*
     * E-commerce Report Controller 
     */
    public function profitLoss(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ecommerce Reports', 'subTitle' => 'Profit and Loss Report' ]);        
        return view('admin.report.ecom.profitloss.profitlossform');
    }

    public function getprofitloss(Request $request){
        //converting date format from m-d-Y to Y-m-d as database stroes date in 'Y-m-d' format
        $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');
        $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');
                 
        // profit-loss calculation
        if('productwise'== $request->profitLossOption){ // productwise profit-loss report
            $time_sales = DB::table('cartbackups')
                ->join('recipes', 'cartbackups.product_id', '=', 'recipes.product_id') 
                ->join('orders', 'orders.id', '=', 'cartbackups.order_id')   
                ->select('cartbackups.product_id',  DB::raw('recipes.production_food_cost*SUM(product_quantity) as salesCost, unit_price * SUM(product_quantity) as sales, SUM(product_quantity) as total_qty'))
                ->where('orders.status','delivered')
                ->whereDate('cartbackups.created_at', '>=', $start_date)
                ->whereDate('cartbackups.created_at', '<=', $end_date)
                ->groupBy('cartbackups.product_id', 'unit_price', 'recipes.production_food_cost')               
                ->get();

            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Ecommerce Reports', 'subTitle' => 'Profit and Loss Report' ]);
            return view('admin.report.ecom.profitloss.profitlossproduct')->with([
                'time_sales' => $time_sales,
                'start_date' => $start_date,
                'end_date'  => $end_date,
                'productwise'=> 1, // productwise true
                'total_sales' => $this->getGrandTotal($start_date, $end_date)->total_sales, 
            ]);
        }else{ // summary profit-loss report 
            $time_sales = DB::table('cartbackups')
                ->join('recipes', 'cartbackups.product_id', '=', 'recipes.product_id') 
                ->join('orders', 'orders.id', '=', 'cartbackups.order_id')            
                ->select(DB::raw('unit_price * SUM(product_quantity) as sales, recipes.production_food_cost*SUM(product_quantity) as salesCost')) 
                ->where('orders.status', 'delivered')
                ->whereDate('cartbackups.created_at', '>=', $start_date)
                ->whereDate('cartbackups.created_at', '<=', $end_date)                
                ->groupBy('unit_price', 'recipes.production_food_cost')->get();

            // Attaching pagetitle and subtitle to view.
            view()->share(['pageTitle' => 'Ecommerce Reports', 'subTitle' => 'Profit and Loss Report' ]);
            return view('admin.report.ecom.profitloss.summaryprofitloss')->with([
                'time_sales' => $time_sales,
                'start_date' => $start_date,
                'end_date'  => $end_date,
                'summary'=> 1, // summary report true
                'total_sales' => $this->getGrandTotal($start_date, $end_date)->total_sales, 
            ]);
        }
        
    }

    public function pdfgetprofitloss($start_date, $end_date, $option){   
        
        // Getting grand totals of total orders of the specified date.
        $total_sales = $this->getGrandTotal($start_date, $end_date)->total_sales;

        if('product'== $option){ // productwise profit-loss report
            $time_sales = DB::table('cartbackups')
            ->join('recipes', 'cartbackups.product_id', '=', 'recipes.product_id') 
            ->join('orders', 'orders.id', '=', 'cartbackups.order_id')   
            ->select('cartbackups.product_id',  DB::raw('recipes.production_food_cost*SUM(product_quantity) as salesCost, unit_price * SUM(product_quantity) as sales, SUM(product_quantity) as total_qty'))
            ->where('orders.status','delivered')
            ->whereDate('cartbackups.created_at', '>=', $start_date)
            ->whereDate('cartbackups.created_at', '<=', $end_date)
            ->groupBy('cartbackups.product_id', 'unit_price', 'recipes.production_food_cost')               
            ->get();
            
            $pdf = PDF::loadView('admin.report.ecom.pdf.pdfproductprofitloss', compact('time_sales', 'start_date', 'end_date','total_sales'))
            ->setPaper('a4', 'potrait');
            return $pdf->stream('pdfproductprofitloss.pdf');

        }else{ // summarywise profit-loss report
            $time_sales = DB::table('cartbackups')
            ->join('recipes', 'cartbackups.product_id', '=', 'recipes.product_id') 
            ->join('orders', 'orders.id', '=', 'cartbackups.order_id')            
            ->select(DB::raw('unit_price * SUM(product_quantity) as sales, recipes.production_food_cost*SUM(product_quantity) as salesCost')) 
            ->where('orders.status', 'delivered')
            ->whereDate('cartbackups.created_at', '>=', $start_date)
            ->whereDate('cartbackups.created_at', '<=', $end_date)                
            ->groupBy('unit_price', 'recipes.production_food_cost')->get();
            
            $pdf = PDF::loadView('admin.report.ecom.pdf.pdfsummaryprofitloss', compact('time_sales', 'start_date', 'end_date', 'total_sales'))
            ->setPaper('a4', 'potrait');
            return $pdf->stream('pdfsummaryprofitloss.pdf');
        }
    }

     // getting orders grand_total
     public function getGrandTotal($start_date, $end_date){

        $grandTotal = DB::table('orders')
        ->select(DB::raw('SUM(grand_total) as total_sales'))
        ->where('orders.status', 'delivered')
        ->whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->first();

        return $grandTotal;
    }


    public function cashregister(){
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ecommerce Reports', 'subTitle' => 'Cash Register Wise Sales Report' ]);        
        return view('admin.report.ecom.cashRegister.cashRegisterForm');
    }

    public function getcashregister(Request $request){
        //converting date format from m-d-Y to Y-m-d as database stroes date in 'Y-m-d' format
        $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');
        $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');
        
        $cash_sales = 0.0;
        $mobile_sales = 0.0;
        $ib = 0.0;
        $card_sales=0.0;
        //total sales 
        $net_sales = 0.0;

        //cash register wise report
        $cashRegister = DB::table('orders')
        ->select('order_number','grand_total','order_date', 'payment_method', 'item_count','amount','card_brand')
        ->where('status', 'delivered')
        ->whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->orderByRaw('order_number DESC')
        ->get();

        //accumulating sales,
        foreach($cashRegister as $cash){
            if(NULL == $cash->amount){
                $cash_sales += $cash->grand_total;
            }elseif('MOBILEBANKING' == $cash->card_brand){
                $mobile_sales += $cash->amount;
            }elseif('IB' == $cash->card_brand){
                $ib += $cash->amount; 
            }else{
                $card_sales += $cash->amount;
            } 
            //total sales
            $net_sales += $cash->grand_total;              
        }
        
        // Attaching pagetitle and subtitle to view.
        view()->share(['pageTitle' => 'Ecommerce Reports', 'subTitle' => 'Cash Register Wise Sales Report' ]);
        return view('admin.report.ecom.cashRegister.cashregisterview')->with([
            'cash_register' => $cashRegister,
            'start_date' => $start_date,
            'end_date'  => $end_date,
            'net_sales' => $net_sales,
            'cash_sales' => $cash_sales,
            'card_sales' => $card_sales,
            'mobile_sales'=> $mobile_sales,
            'ib'=> $ib,
        ]);
    }

    public function pdfgetcashregister($start_date, $end_date){
        
        $cash_sales = 0.0;
        $mobile_sales = 0.0;
        $ib = 0.0;
        $card_sales=0.0;
        //total sales 
        $net_sales = 0.0;

        //cash register wise report
        $cashRegister = DB::table('orders')
        ->select('order_number','grand_total','order_date', 'payment_method', 'item_count','amount','card_brand')
        ->where('status', 'delivered')
        ->whereDate('created_at', '>=', $start_date)
        ->whereDate('created_at', '<=', $end_date)
        ->orderByRaw('order_number DESC')
        ->get();

        //accumulating sales,
        foreach($cashRegister as $cash){
            if(NULL == $cash->amount){
                $cash_sales += $cash->grand_total;
            }elseif('MOBILEBANKING' == $cash->card_brand){
                $mobile_sales += $cash->amount;
            }elseif('IB' == $cash->card_brand){
                $ib += $cash->amount; 
            }else{
                $card_sales += $cash->amount;
            } 
            //total sales
            $net_sales += $cash->grand_total;              
        }

        $pdf = PDF::loadView('admin.report.ecom.pdf.pdfgetcashregister', compact('cashRegister', 'start_date', 'end_date', 
        'net_sales', 'cash_sales', 'card_sales', 'mobile_sales', 'ib'))
            ->setPaper('a4', 'potrait');
            return $pdf->stream('pdfgetcashregister.pdf');
    }


    
    // public function dailyCarts(){

    //     //Product wise daily sale reports 
    //     $daily_carts  = DB::table('cartbackups')
    //             ->select('product_id', 'unit_price', 'product_attribute_id', DB::raw('SUM(product_quantity) as total_qty, unit_price * SUM(product_quantity) as subtotal')) 
    //             ->whereRaw('order_id is not NULL and order_cancel = 0 and date(created_at) = CURDATE() - INTERVAL 1 DAY')
    //             ->groupBy('product_id', 'unit_price', 'product_attribute_id')
    //             ->orderByRaw('SUM(product_quantity) DESC')
    //             ->get();

    //     return $daily_carts;
    // }    
    
    // public function daily(){
    //     $daily_carts = $this->dailyCarts();
    //     // Attaching pagetitle and subtitle to view.
    //     view()->share(['pageTitle' => 'Daliy Sale', 'subTitle' => 'Product wise Daily Sale' ]);
    //     return view('admin.report.daily', compact('daily_carts'));     
    // }

    // public function pdfdaily(){        
    //     $daily_carts = $this->dailyCarts();
    //     $pdf = PDF::loadView('admin.report.pdf.pdfdaily', compact('daily_carts'));
    //     return $pdf->stream('dailyitemsale.pdf');
    // }

    // public function exceldaily(){ 
    //     return Excel::download(new DailyExport(), 'daily_report.xlsx');
    // }

    // public function dailyTotal(){  
    //     //Day wise sale reports 
    //     $daily_totals = DB::table('cartbackups')
    //         ->select( DB::raw('Date(created_at) as date, SUM(product_quantity * unit_price ) as subtotal')) 
    //         ->whereRaw('order_id is not NULL and order_cancel = 0')
    //         ->groupByRaw('Date(created_at)')
    //         ->orderByRaw('Date(created_at) DESC')
    //         ->paginate(15);       
    //     // Attaching pagetitle and subtitle to view.
    //     view()->share(['pageTitle' => 'Daily Sale', 'subTitle' => 'Per Day Sale Amount' ]);
    //     return view('admin.report.daytotal', compact('daily_totals'));
    // }

    // public function pdfDailyTotal(){
    //     //Day wise sale reports 
    //     $daily_totals = DB::table('cartbackups')
    //     ->select( DB::raw('Date(created_at) as date, SUM(product_quantity * unit_price ) as subtotal')) 
    //     ->whereRaw('order_id is not NULL and order_cancel = 0')
    //     ->groupByRaw('Date(created_at)')
    //     ->orderByRaw('Date(created_at) DESC')
    //     ->get();  // in pdf we have limited only 20 days

    //     $pdf = PDF::loadView('admin.report.pdf.pdfdaytotal', compact('daily_totals'));
    //     return $pdf->stream('dailysale.pdf');
    // }

    // public function excelDailyTotal(){
    //     return Excel::download(new DailyTotalExport(), 'daily_total.xlsx');
    // }

    
    // public function monthlytotal(){
    //     //Month wise sale reports 
    //     $monthly_totals = DB::table('cartbackups')
    //     ->select( DB::raw('DATE_FORMAT(created_at, "%Y-%m") as yearMonth, SUM(product_quantity * unit_price ) as subTotal')) 
    //     ->whereRaw('order_id is not NULL and order_cancel = 0')
    //     ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')         
    //     ->orderByRaw('DATE_FORMAT(created_at, "%Y-%m") DESC')
    //     ->paginate(12);
   
    //     // Attaching pagetitle and subtitle to view.
    //     view()->share(['pageTitle' => 'Monthly Sale', 'subTitle' => 'Per Month Sale Amount' ]);
    //     return view('admin.report.monthtotal', compact('monthly_totals'));        
    // }

    // public function pdfMonthlyTotal(){

    //      //Month wise sale reports 
    //      $monthly_totals = DB::table('cartbackups')
    //      ->select( DB::raw('DATE_FORMAT(created_at, "%Y-%m") as yearMonth, SUM(product_quantity * unit_price ) as subtotal')) 
    //      ->whereRaw('order_id is not NULL and order_cancel = 0')
    //      ->groupByRaw('DATE_FORMAT(created_at, "%Y-%m")')         
    //      ->orderByRaw('DATE_FORMAT(created_at, "%Y-%m") DESC')
    //      ->get();

    //      $pdf = PDF::loadView('admin.report.pdf.pdfmonthtotal', compact('monthly_totals'));
    //      return $pdf->stream('monthlysale.pdf');
    // }

    // public function excelMonthlyTotal(){        
    //     return Excel::download(new MonthlyTotalExport(), 'monthly_total.xlsx');
    // }

    // public function yearlytotal(){
    //     //Year based sale reports 
    //     $yearly_totals = DB::table('cartbackups')
    //         ->select( DB::raw('Year(created_at) as year, SUM(product_quantity * unit_price ) as subTotal')) 
    //         ->whereRaw('order_id is not NULL and order_cancel = 0')
    //         ->groupByRaw('Year(created_at)')         
    //         ->orderByRaw('Year(created_at) DESC')
    //         ->paginate(10);
   
    //     // Attaching pagetitle and subtitle to view.
    //     view()->share(['pageTitle' => 'Yearly Sale', 'subTitle' => 'Year Based Sale ' ]);
    //     return view('admin.report.yeartotal', compact('yearly_totals'));   
    // }

    // public function pdfYearlyTotal(){
    //          //Year based sale reports 
    //     $yearly_totals = DB::table('cartbackups')
    //         ->select( DB::raw('Year(created_at) as year, SUM(product_quantity * unit_price ) as subtotal')) 
    //         ->whereRaw('order_id is not NULL and order_cancel = 0')
    //         ->groupByRaw('Year(created_at)')         
    //         ->orderByRaw('Year(created_at) DESC')
    //         ->get();
        
    //     $pdf = PDF::loadView('admin.report.pdf.pdfyeartotal', compact('yearly_totals'));
    //     return $pdf->stream('yearlysale.pdf');
    // }

    // public function excelYearlyTotal(){
    //     return Excel::download(new YearlyTotalExport(), 'yearly_total.xlsx');
    // }


    // public function top20(){
    //     // Attaching pagetitle and subtitle to view.
    //     view()->share(['pageTitle' => 'Top 20', 'subTitle' => 'List of Top 20 Sale Items' ]);        
    //     return view('admin.report.itemform');
    // }

    // public function getTop20(Request $request){
    //     //coverting date format from m-d-Y to Y-m-d as database stores date in 'Y-m-d' format
    //     $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');
    //     $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');      
    
    //     //Top20 sale reports 
    //     $time_carts = DB::table('cartbackups')
    //             ->select('product_id', 'unit_price', 'product_attribute_id', DB::raw('SUM(product_quantity) as total_qty, unit_price * SUM(product_quantity) as subtotal')) 
    //             ->whereRaw('order_id is not NULL and order_cancel = 0')
    //             ->whereBetween('created_at', [$start_date, $end_date])
    //             ->groupBy('product_id', 'unit_price', 'product_attribute_id')
    //             ->orderByRaw('SUM(product_quantity) DESC')
    //             ->get();
        
    //     // Attaching pagetitle and subtitle to view.
    //     view()->share(['pageTitle' => 'Top20 Sale', 'subTitle' => 'Anytime Top20 Food Sale' ]);
    //     return view('admin.report.top20')->with([
    //         'time_carts' => $time_carts,
    //         'start_date' => $start_date,
    //         'end_date'  => $end_date,
    //     ]);
        
    // }


    // public function pdfGetTop20($start_date, $end_date){       

    //     $time_carts = DB::table('cartbackups')
    //             ->select('product_id', 'unit_price', 'product_attribute_id', DB::raw('SUM(product_quantity) as total_qty, unit_price * SUM(product_quantity) as subtotal')) 
    //             ->whereRaw('order_id is not NULL and order_cancel = 0')
    //             ->whereBetween('created_at', [$start_date, $end_date])
    //             ->groupBy('product_id', 'unit_price', 'product_attribute_id')
    //             ->orderByRaw('SUM(product_quantity) DESC')
    //             ->get();

    //     $pdf = PDF::loadView('admin.report.pdf.pdftop20', compact('time_carts', 'start_date', 'end_date'));
    //     return $pdf->stream('AnytimeTop20sale.pdf');
    // }

    // public function excelGetTop20($start_date, $end_date){
    //     return Excel::download(new ItemBasedExport($start_date, $end_date), 'Item_based_sale.xlsx');
    // }
    
    // public function single(){
    //     // Attaching pagetitle and subtitle to view.
    //     view()->share(['pageTitle' => 'Individual Item Sale', 'subTitle' => 'Individual Item Sale Details' ]);        
    //     return view('admin.report.singleitemform');
    // }

    // public function singleSale(Request $request){
    //     //coverting date format from m-d-Y to Y-m-d as database stroes date in 'Y-m-d' format
    //     $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->format('Y-m-d');
    //     $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->format('Y-m-d');      
        
    //     $search = $request->search_food;      
    //     $product = Product::where('name', 'like', '%'.$search.'%')->first();

    //     if($product){
    //     //single item sale reports 
    //         $single_carts = DB::table('cartbackups')
    //                 ->select('product_id', 'unit_price', 'product_attribute_id', DB::raw('Date(created_at) as date, SUM(product_quantity) as total_qty, unit_price * SUM(product_quantity) as subtotal')) 
    //                 ->whereRaw('order_id is not NULL and order_cancel = 0')
    //                 ->whereBetween('created_at', [$start_date, $end_date])
    //                 ->where('product_id', $product->id)
    //                 ->groupByRaw('Date(created_at), product_id, unit_price, product_attribute_id')
    //                 ->orderByRaw('Date(created_at) DESC')
    //                 ->get();           
            
    //         //Attaching pagetitle and subtitle to view.
    //         view()->share(['pageTitle' => 'Individual Item Sale', 'subTitle' => 'Individual Item Sale Details' ]);
    //         return view('admin.report.singleitem')->with([
    //             'single_carts' => $single_carts,
    //             'start_date' => $start_date,
    //             'end_date'  => $end_date,
    //             'search_food' => $search,
    //         ]);
    //     }      
    //     return $this->responseRedirectBack('The given food search key is invalid.' ,'error', false, false); 
       
    // }

    // public function pdfSingleSale($start_date, $end_date, $search){

    //     $product = Product::where('name', 'like', '%'.$search.'%')->first();
    //     //single item sale reports 
    //     $single_carts = DB::table('cartbackups')
    //             ->select('product_id', 'unit_price', 'product_attribute_id', DB::raw('Date(created_at) as date, SUM(product_quantity) as total_qty, unit_price * SUM(product_quantity) as subtotal')) 
    //             ->whereRaw('order_id is not NULL and order_cancel = 0')
    //             ->whereBetween('created_at', [$start_date, $end_date])
    //             ->where('product_id', $product->id)
    //             ->groupByRaw('Date(created_at), product_id, unit_price, product_attribute_id')
    //             ->orderByRaw('Date(created_at) DESC')
    //             ->get(); 
        
    //     $pdf = PDF::loadView('admin.report.pdf.pdfsingleitem', compact('single_carts', 'start_date', 'end_date'));
    //     return $pdf->stream('AnytimeSingleSale.pdf');

    // }

    // public function excelSingleSale($start_date, $end_date, $search){        
    //     return Excel::download(new SingleItemExport($start_date, $end_date, $search), 'Single_item_sale.xlsx');
    // }

  
   
}
