<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ingredient; 
use App\Mail\IngredientUpdate;
use PDF;

// cron-job-task-scheduling 
class DailyIngredient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:ingredientUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a daily ingredient purchase list to backend users';

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
        // we will implement the logic here
        // when comparing two columns we will use whereColumn
        $ingredients = Ingredient::whereColumn('total_quantity', '<', 'alert_quantity')->get();
        //getting backend email recipients
        //$cc = explode(',', str_replace(' ', '', ));  
	    // $cc = 'mustafi.amana@gmail.com';
        
	    //getting backend email recipients
        $email_recipients = explode(',', str_replace(' ', '', config('settings.email_recipient'))); 
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
            ->send(new IngredientUpdate($ingredients));
        //info function will write information to the log:
        $this->info('Daily ingredient Update has been send successfully');

    }
}
