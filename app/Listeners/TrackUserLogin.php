<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;

class TrackUserLogin implements ShouldQueue
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        // Update the user's last login timestamp
        $user = $event->user;
        $user->last_login = now();
        $user->save();
    }
}
