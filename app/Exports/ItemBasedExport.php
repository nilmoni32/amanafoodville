<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class ItemBasedExport implements FromCollection, WithHeadings
{
    protected $start_date, $end_date;

    public function __construct(string $start_date, string $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        return DB::table('cartbackups')
            ->join('products', 'cartbackups.product_id', '=', 'products.id')            
            ->select('products.name', 'unit_price', DB::raw('SUM(product_quantity) as total_qty, unit_price * SUM(product_quantity) as subtotal')) 
            ->whereRaw('order_id is not NULL and order_cancel = 0')
            ->whereBetween('cartbackups.created_at', [$this->start_date, $this->end_date])
            ->groupBy('products.name','unit_price')
            ->orderByRaw('SUM(product_quantity) DESC')
            ->get();  
        
           
    }

    public function headings(): array
    {
        return [
            'Food Name',
            'Unit Price',
            'Total Qty',
            'Subtotal',
        ];
    }
}