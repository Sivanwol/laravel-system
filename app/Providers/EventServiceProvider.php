protected $listen = [
Registered::class => [
SendEmailVerificationNotification::class,
],
\Illuminate\Auth\Events\Login::class => [
\App\Listeners\TrackUserLogin::class,
],
'user.invited' => [
\App\Listeners\SendUserInvitation::class,
],
];
