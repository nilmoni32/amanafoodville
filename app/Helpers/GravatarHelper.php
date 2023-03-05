<?php
namespace App\Helpers;

use App\Helpers\GravatarHelper;

class GravatarHelper{

/**
 * validate gravatar
 * Check if the email has any gravatar image or not
 * @param string $email : user email
 * @return string $img_url 
 */

    public static function Gravatar($email){
        $gravemail = md5($email);
        $gravsrc = "http://www.gravatar.com/avatar/".$gravemail;
        $gravcheck = "http://www.gravatar.com/avatar/".$gravemail."?d=404";
        $response = get_headers($gravcheck);     
        if (!preg_match("|200|", $response[0])){           
            $img_url = url('/frontend/images/user.png');
        }else{
            $img_url = $gravsrc;
        }
        return $img_url;
    }
}