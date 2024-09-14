<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        clock()->info("User {$user->getAuthIdentifier()} logged in!", ['user' => $user->toArray()]);
        // Your custom logic here
        activity()
            ->causedBy($user)
            ->log('Logged in');
    }
}
