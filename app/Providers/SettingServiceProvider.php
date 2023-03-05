<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Config;
/**
 * To register Setting model as Facade using this service provider so that we can use Setting:get() or Setting::set() 
 */

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // here $this->app is an instance of the laravel conatiner which is always available on every service provider
        // bind is used to bind our Setting model class to the container.
        // the first parameter 'setting' of bind() is the key that we are binding to.
        // the second parameter of bind() is a closer function also have the parameter the container itself.
        $this->app->bind('setting', function($app){
            return new Setting();
        });

        //using alias loader class to register the aliase of our facade which we usually do by adding into the alias array in app.php file.
        //so we are skipping the step of creating SettingFacade class and also register it as aliases in config/app.php.
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Setting', Setting::class);
    }

    /**
     * Bootstrap services.
     * Loading all our settings when our application boot up, so we can use the setting anywhere we want it
     * @return void
     */
    public function boot()
    {
        //only use the Settings package if the settings table is present in the database.
        if (count(Schema::getColumnListing('settings'))) {
            // get all settings from the database
            $settings = Setting::all();

            // bind all settings to the Laravel config, so you can call them like
            // Config::get('settings.contact_email')
            foreach ($settings as $key => $setting) {
                Config::set('settings.'.$setting->key, $setting->value);
            }
        }
    }
}
