<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReferenceAuthority extends Mailable
{
    use Queueable, SerializesModels;
    protected $discount_reference;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ref_data)
    {
        $this->discount_reference = $ref_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Funville POS Reference Discount Deatils')
        ->view('mail.email.refDiscount')->with('reference_discount', $this->discount_reference);        
    }
}
