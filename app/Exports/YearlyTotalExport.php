<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class YearlyTotalExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        //Month wise sale reports 
        return DB::table('cartbackups')
            ->select( DB::raw('Year(created_at) as year, SUM(product_quantity * unit_price ) as subtotal')) 
            ->whereRaw('order_id is not NULL and order_cancel = 0')
            ->groupByRaw('Year(created_at)')         
            ->orderByRaw('Year(created_at) DESC')
            ->get();       
    }

    public function headings(): array
    {
        return [
            'Year',
            'Total Amount(Tk)',  
        ];
    }
}