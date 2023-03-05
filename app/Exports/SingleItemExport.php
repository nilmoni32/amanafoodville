<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Product;


class SingleItemExport implements FromCollection, WithHeadings
{
    protected $start_date, $end_date, $search;

    public function __construct(string $start_date, string $end_date, string $search)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->search = $search;
    }

    public function collection()
    {
        $product = Product::where('name', 'like', '%'.$this->search.'%')->first();
        //single item sale reports 
        return DB::table('cartbackups')
                ->join('products', 'cartbackups.product_id', '=', 'products.id')                
                ->select(DB::raw('Date(cartbackups.created_at) as date, products.name, unit_price, SUM(product_quantity) as total_qty, unit_price * SUM(product_quantity) as subtotal')) 
                ->whereRaw('order_id is not NULL and order_cancel = 0')
                ->whereBetween('cartbackups.created_at', [$this->start_date, $this->end_date])
                ->where('cartbackups.product_id', $product->id)
                ->groupByRaw('Date(cartbackups.created_at), products.name, unit_price')
                ->orderByRaw('Date(cartbackups.created_at) DESC')
                ->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Food Name',            
            'Unit Price',
            'Total Qty',
            'Subtotal',
        ];
    }
}