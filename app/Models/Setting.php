<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Config;

/**
 * Using setting section we will be setting site name, logos, shipping methods control, payment methods control and so on for this ecommerce application.
 */
class Setting extends Model
{
    /**
     * @var string
     */
   protected $table = "settings";

   /**
    * mass assignable parameters.
    * @var array
    */

   protected $fillable = ['key','value'];

   /**
    * Setting Model get method: to get the setting value by querying the $key
    * @param $key
    */

    public static function get($key){
        $setting = new self();      // getting the setting model object within it's model
        $entry = $setting->where('key', $key)->first(); // getting the first record. here key is the column name of settings table.
        if(!$entry){
            return;
        }

        return $entry->value;
    }

    /**
     * Setting Model set method: We will use it to update the settings value.
     * @param $key
     * @param null $value
     * @return bool
     */

     public static function set($key, $value=null){
        $setting = new self();
        // firstOrFail(): returns the first record found in the database. If no matching model exist, it throws an error.
        $entry = $setting->where('key', $key)->firstOrFail(); 
        $entry->value = $value; // here $entry->value is the database field.
        $entry->saveOrFail(); // saveOrFail() ensures that if there were any exceptions raised during save(), the model would not have been saved.
        // setting the current key/value for setting to the Laravel Configuration, so we can load them using the Laravel config() helper function.
        Config::set($key,$value);
        if(Config::get($key) == $value){
            return true;
        }
        return false;
     }

     
}
