<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

// cron-job-task-scheduling 
class IngredientUpdate extends Mailable
{
    use Queueable, SerializesModels;
    public $ingredients;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ingredients)
    {
        $this->ingredients = $ingredients;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
     
        //pdf attachment for ingredients.  
         $pdf = PDF::loadView('admin.report.pdf.pdfingredient', ['ingredients' => $this->ingredients]); 

        return $this->subject('Funville Daily Ingredient Purchase List')
                    ->view('mail.email.ingredientUpdate')                 
                    ->with('ingredients', $this->ingredients)
                    ->attachData($pdf->output(), 'ingredient.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
