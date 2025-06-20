<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendUserInvitation implements ShouldQueue
{
    /**
     * Handle the user invitation event.
     */
    public function handle(object $event): void
    {
        $user = $event;

        if ($event instanceof User) {
            // Send invitation email with password reset link
            $user->sendPasswordResetNotification(
                app('auth.password.broker')->createToken($user)
            );
        }
    }
}
