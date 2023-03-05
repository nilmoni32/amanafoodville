<?php

namespace App\Traits;

//To show flash messages to users, we use the Laravel’s flash messages helper session()->flash()

/**
 * Trait FlashMessages
 * @package App\Traits
 */

trait FlashMessages{

    /**
     * @var array
     */
    protected $errorMessages = [];

    /**
     * @var array
     */
    protected $infoMessages =  [];

    /**
     * @var array
     */
    protected $successMessages = [];

     /**
     * @var array
     */
    protected $warningMessages = [];

    // We need to add setter and getter functions for flash messages.    

    /**
     * @param $message
     * @param $type
     */

     Protected function setFlashMessage($message, $type){
         // we set the flash messages of all types to Laravel session

        $model = 'infoMessages';

        switch($type){

            case 'info':
                $model = 'infoMessages';
                break;
            case 'error':
                $model = 'errorMessages';
                break;
            case 'success':
                $model = 'successMessages';
                break;
            case 'warning':
                $model = 'warningMessages';
                break;
            }
            
            if(is_array($message)){
                foreach($message as $key => $value){
                    // According to the type value we are getting $model string value and then pushing the message 
                    // to the corresponding array. e.g for default value of $model, $this->$model refers to $infoMessages[] array. 
                    array_push($this->$model, $value);
                }
            }else{
                array_push($this->$model, $message);
            }
       
     }

     /**
      * @return array
      */
     protected function getFlashMessage()
     {
        return [
            'info'  => $this->infoMessages,
            'error' => $this->errorMessages,
            'warning' => $this->warningMessages,
            'success' => $this->successMessages
        ];
     }

     /**
      * Flashing flash data to laravel's session.
      * We use flash method to store data in session for next http request on temporary basis.
      */
      protected function showFlashMessages(){

        session()->flash('info', $this->infoMessages);
        session()->flash('error', $this->errorMessages);
        session()->flash('warning', $this->warningMessages);
        session()->flash('success', $this->successMessages);
        
      }
    
}