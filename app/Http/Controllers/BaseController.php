<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\FlashMessages; 

/**
 * Using BaseController we will set all views title & subtitle.
 * Using BaseController we will set flash messages in order to show in view, here we will use our custom trait FlashMessages. 
 */
class BaseController extends Controller
{
    use FlashMessages;
    protected $data = 0;  // we will add error messages here.

    /**
     * @param $title
     * @param $subtitle
     * We are attaching data (title and subtitle) with all views using view()->share() method. 
     */

    protected function setPageTitle($title, $subTitle){

        view()->share(['pageTitle' => $title, 'subTitle' => $subTitle ]);
    }

    /**
     * To show error page with our custom message and type of error page we want to load.
     * @param int $errorCode
     * @param null $message 
     * return \Illuminate\Http\Response
     *  when we want to send a response to user with status code and status content, we use view as response content
     *  here we are loading an error view from errors folder based on error code and passing data array to error view
     */

    protected function showErrorPage($errorCode = 404, $message = null){
        $data['message'] = $message;
        return response()->view('errors.'.$errorCode, $data, $errorCode);
    }

    /**
     * Returning a JSON data response to the user, If we are using ajax or VueJs in our application.
     * @param bool $error
     * @param array $message
     * @param int $responseCode
     * @param null $data
     */

     protected function responseJson( $error = true, $message = [], $responseCode = 200, $data = null){

        return response()->json([
            'error' => $error,
            'message' => $message,
            'response_code' => $responseCode,
            'data' => $data
        ]);
     }

     /**
      * when an http request comes, we need to send normal reponse back to user, so we redirect it to a page or requested route
      * @param $route
      * @param $message
      * @param string $type
      * @param bool $error
      * @param bool $withOldInputWhenError
      * @return \Illuminate\Http\RedirectResponse
      */

      protected function responseRedirect($route, $message, $type = 'info', $error = false, $withOldInputWhenError = false){

        $this->setFlashMessage($message, $type);    // setting flash message using trait
        $this->showFlashMessages();                 // displaying flash messages using trait
        if($error && $withOldInputWhenError){       // if an error is occurred then we are returning back 
            return redirect()->back()->withInput();
        } 

        return redirect()->route($route);           // if no error is occurred then we redirecting the request to the provided route.
      }

      /**       
       * Sometimes we need to redirect the user to the previous page. 
       * for example, when a user update an category, we send the user back to the same category.
       * so setting flash messages and then returning back
       * @param $message
       * @param $type
       * @param bool $error
       * @param bool $withOldInputWhenError
       * @return \Illuminate\Http\RedirectResponse
       */

       protected function responseRedirectBack($message, $type = 'info', $error = false, $withOldInputWhenError = false){

        $this->setFlashMessage($message, $type);    // setting flash message using trait
        $this->showFlashMessages();                 // displaying flash messages using trait            
        return redirect()->back();
       }

       

}
