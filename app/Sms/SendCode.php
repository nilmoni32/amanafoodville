<?php

namespace App\Sms;
use GuzzleHttp\Client;

class SendCode{

    public static function sendCode($mobile, $code){   

        // Nexmo package : to send sms to mobile number.
                                 
        // $nexmo = app('Nexmo\Client');
        // $nexmo->message()->send([
        //     'to'   => ,
        //     'from' => 'Amana Funville',
        //     'text' => 'Verification Code:' . $code,
        // ]);  

        // GuzzleHttp\Client : send message to mobile number   
        
        $client = new Client();
        $url = "https://gpcmp.grameenphone.com/ecmapigw/webresources/ecmapigw.v2";
        $headers =  [ 'Accept' => 'application/json', 'Content-Type' => 'application/json'];
        $params = [
                 'username' => 'AGLAdmin_4548',
                 'password' => 'Amana@2010',
                 'apicode'  =>  '1',
                 'msisdn'   =>  $mobile,
                 'countrycode' =>  '880',
                 'cli'       =>  'FUNVILLE',
                 'messagetype'=>  '1',
                 'message'    =>  'verification code: '. $code,
                 'messageid'  =>  '0',
         ];

         $response = $client->request('POST', $url, ["headers" => $headers, "json" => $params]);
         $result = json_decode($response->getBody());
        


        //php curl method : sending message to mobile.

        // $post_url = 'https://portal.smsinbd.com/smsapi' ;

          //  $post_values = array(
          //  'api_key' => 'b3c6b0eda3b46d9878df961d4c1c6b07d6cf886d',
         //   'type' => 'text', 
         //   'senderid' => '8801552146120',
         //   'contacts' => '880'. (int) $mobile,
         //   'msg' => 'Verification Code: ' . $code,
         //   'method' => 'api'
         //   );

         //   $post_string = "";
         //   foreach( $post_values as $key => $value )
         //   { $post_string .= "$key=" . urlencode( $value ) . "&"; }
         //   $post_string = rtrim( $post_string, "& " );


        //    $request = curl_init($post_url);
        //    curl_setopt($request, CURLOPT_HEADER, 0);
        //    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        //    curl_setopt($request, CURLOPT_POST, 1);
        //    curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
        //    curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
        //    $post_response = curl_exec($request);
        //    curl_close ($request);


 
    }

    //send sms notification to director as discount reference while giving discount to customer 
    public static function sendDiscountAmount($mobile, $order_no, $discount_limit, $discount){
        
        //php curl method : sending message to mobile.

        $post_url = 'https://portal.smsinbd.com/smsapi' ;

        $post_values = array(
        'api_key' => 'b3c6b0eda3b46d9878df961d4c1c6b07d6cf886d',
        'type' => 'text', 
        'senderid' => '8801552146120',
        'contacts' => '880'. (int) $mobile,
        'msg' => 'Funville Order No: ' . $order_no . ' received discount: ' . $discount . ' Tk with discount limit: ' . $discount_limit . ' Tk',
        'method' => 'api'
        );

        $post_string = "";
        foreach( $post_values as $key => $value )
        { $post_string .= "$key=" . urlencode( $value ) . "&"; }
        $post_string = rtrim( $post_string, "& " );


        $request = curl_init($post_url);
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POST, 1);
        curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
        $post_response = curl_exec($request);
        curl_close ($request);
    }

    //send payment notification to customer for cash or card or mobile banking
    public static function paymentNotify($mobile, $paid_amount, $points, $method){
        //php curl method : sending message to mobile.

        $post_url = 'https://portal.smsinbd.com/smsapi' ;

        $post_values = array(
        'api_key' => 'b3c6b0eda3b46d9878df961d4c1c6b07d6cf886d',
        'type' => 'text', 
        'senderid' => '8801552146120',
        'contacts' => '880'. (int) $mobile,
        'msg' => 'Your '.$method.' payment: ' . $paid_amount . 'Tk has received and your reward points is: ' .$points. '. Thanks for coming to our Restaurant -- Amana Funville.',
        'method' => 'api'
        );

        $post_string = "";
        foreach( $post_values as $key => $value )
        { $post_string .= "$key=" . urlencode( $value ) . "&"; }
        $post_string = rtrim( $post_string, "& " );


        $request = curl_init($post_url);
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POST, 1);
        curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
        $post_response = curl_exec($request);
        curl_close ($request);
    }

    //send payment notification to customer for both payment mode such as cash, card
    public static function twoPaymentNotify($mobile, $paid_amount1, $paid_amount2, $points, $payment_mode1, $payment_mode2){
        //php curl method : sending message to mobile.

        $post_url = 'https://portal.smsinbd.com/smsapi' ;

        $post_values = array(
        'api_key' => 'b3c6b0eda3b46d9878df961d4c1c6b07d6cf886d',
        'type' => 'text', 
        'senderid' => '8801552146120',
        'contacts' => '880'. (int) $mobile,
        'msg' => 'Your '.$payment_mode1.' payment: ' . $paid_amount1 . 'Tk and '.$payment_mode2.' payment: '.$paid_amount2.'Tk has received and your reward points is: ' .$points. '. Thanks for coming to our Restaurant -- Amana Funville.',
        'method' => 'api'
        );

        $post_string = "";
        foreach( $post_values as $key => $value )
        { $post_string .= "$key=" . urlencode( $value ) . "&"; }
        $post_string = rtrim( $post_string, "& " );


        $request = curl_init($post_url);
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POST, 1);
        curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
        $post_response = curl_exec($request);
        curl_close ($request);
    }
    
    public static function allPaymentNotify($mobile, $paid_amount1, $paid_amount2, $paid_amount3, $points, $payment_mode1, $payment_mode2,$payment_mode3){
        //php curl method : sending message to mobile.

        $post_url = 'https://portal.smsinbd.com/smsapi' ;

        $post_values = array(
        'api_key' => 'b3c6b0eda3b46d9878df961d4c1c6b07d6cf886d',
        'type' => 'text', 
        'senderid' => '8801552146120',
        'contacts' => '880'. (int) $mobile,
        'msg' => 'Your '.$payment_mode1.' payment: '.$paid_amount1.'Tk,'.$payment_mode2.' payment: '.$paid_amount2.'Tk and'.$payment_mode3.' payment: '.$paid_amount3.'Tk has received and your reward points is: ' .$points. '. Thanks for coming to our Restaurant -- Amana Funville.',
        'method' => 'api'
        );

        $post_string = "";
        foreach( $post_values as $key => $value )
        { $post_string .= "$key=" . urlencode( $value ) . "&"; }
        $post_string = rtrim( $post_string, "& " );


        $request = curl_init($post_url);
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POST, 1);
        curl_setopt($request, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
        $post_response = curl_exec($request);
        curl_close ($request);

    }

}
