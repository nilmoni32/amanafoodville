<?php

namespace App\Providers;

use App\Models\Userlog;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        // Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
        //     //saving log for sign in new user.            
        //     Userlog::sign_in_out("User '{$event->user->name}' has Logged in.");
        // });

        // Event::listen(\Illuminate\Auth\Events\Logout::class, function ($event) {
        //    //saving log for sign out of the current user.
        //    Userlog::sign_in_out("User '{$event->user->name}' has Logged out."); 
        // });
    }
}
