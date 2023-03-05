<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Userlog;
use App\Models\Ordersale;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\AdminPasswordResetNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    // We are using notifiable trait which will be used for password reset notification
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays. 
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];    
  
    // Once we have the reset token, we are ready to send the message out to this
    // Admin user with a link to reset their password. 
    // we will create adminpasswordresetnotification
    public function sendPasswordResetNotification($token){  
        
        $this->notify( new AdminPasswordResetNotification($token, $this->email));
    }

    public function roles(){
        return $this->belongsToMany(Role::class);
    }

    public function hasAnyRoles($roles){
        // here $this is referencing the Admin model itself
        // finding the admin user who has the any role in $roles array
        if($this->roles()->whereIn('name', $roles)->first()){
            return true;
        }
        return false;
    }

    public function hasRole($role){
        // finding the admin user who has the role 
        if($this->roles()->where('name', $role)->first()){
            return true;
        }
        return false;
    }

    public function ordersales(){
        return $this->hasMany(Ordersale::class);  
    }

    /**
     * Admin account have many logs
     */
    public function userlog(){
        return $this->hasMany(Userlog::class);        
    }
   
        
    
}
