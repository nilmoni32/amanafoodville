<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class DailyTotalExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        //Day wise sale reports 
        return DB::table('cartbackups')
            ->select( DB::raw('Date(created_at) as date, SUM(product_quantity * unit_price ) as subtotal')) 
            ->whereRaw('order_id is not NULL and order_cancel = 0 and YEAR(created_at) = YEAR(CURDATE())')
            ->groupByRaw('Date(created_at)')
            ->orderByRaw('Date(created_at) DESC')
            ->get();        
    }

    public function headings(): array
    {
        return [
            'Date',
            'Total Amount(Tk)',  
        ];
    }
}