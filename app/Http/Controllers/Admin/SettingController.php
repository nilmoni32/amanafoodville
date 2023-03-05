<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Models\Setting;
use App\Traits\UploadAble;

class SettingController extends BaseController
{
    
    use UploadAble;

    public function index(){
        //using BaseController setPageTitle()
        $this->setPageTitle('Settings', 'Manage Settings');
        return view('admin.settings.index');
    }

    public function update(Request $request){
        // When you get an uploaded file from a request you get an UploadedFile instance
        // if there is a logo update, then check if the logo is set using the config('settings.site_logo) helper function
        // if there is a logo delete it and upload the new one and set it.        
      
        if ($request->has('site_logo') && ($request->file('site_logo') instanceof UploadedFile)) {           

            if (config('settings.site_logo') != null) {
                $this->deleteOne(config('settings.site_logo')); // here, config('settings.site_logo') is similar to Config('app.name')
            }
            $logo = $this->uploadOne($request->file('site_logo'), 'img');  //$request->file('site_logo') is an image file & 'img' is the folder name.
            // Saving to database using Setting model set method and setting the current key/value for setting to the Laravel Configuration             
            Setting::set('site_logo', $logo); 
    
        } elseif ($request->has('site_favicon') && ($request->file('site_favicon') instanceof UploadedFile)) {
    
            if (config('settings.site_favicon') != null) {
                $this->deleteOne(config('settings.site_favicon'));
            }
            $favicon = $this->uploadOne($request->file('site_favicon'), 'img');
            Setting::set('site_favicon', $favicon);
    
        } else {

            // Load all settings values submitted through the form (except the site_logo and site_favicon)
            $keys = $request->except('_token');
            
            // Loop through all the settings keys and set the value to whatever submitted using the form.
            foreach ($keys as $key => $value)
            {
                Setting::set($key, $value);
            }
        }
        return $this->responseRedirectBack(' Settings updated successfully.', 'success');
       
       
    }
}
