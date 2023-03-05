<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Ordersale;
use App\Mail\KOTRefDiscount;
use Illuminate\Console\Command;


class RefDiscount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'KOT:refdiscount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily KOT Reference Discount list to the authority';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orders = Ordersale::whereNotNull('discount')
        ->whereBetween('order_date', [Carbon::yesterday()->startOfDay()->toDateTimeString(), Carbon::yesterday()->endOfDay()->toDateTimeString()])
        ->get();

        //getting backend email recipients
        $email_recipients = explode(',', str_replace(' ', '', config('settings.ref_email_recipient'))); 
        $cc=[];
        for($i=0; $i< count($email_recipients); $i++){
            //elementing the empty array data fields
            if($email_recipients[$i]){
                $cc[] = $email_recipients[$i]; 
            }
        }

        // sending mail to mailable class IngredientUpdate for the ingredient purchase list to funville backend users.
       \Mail::to(config('settings.default_email_address'))
       //->cc(config('settings.email_recipient'))
        ->cc($cc)
        ->send(new KOTRefDiscount($orders));
        //info function will write information to the log:
        $this->info('Daily KOT Reference Discount list has been sent successfully');

        return 0;
    }
}
