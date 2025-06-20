<?php

use App\Http\Controllers\ProfileController;
use Filament\Facades\Filament;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {
    return view('welcome');
});


// Role assignment helper route (only for development)
if (app()->environment('local')) {
    // Debug route for role management
    Route::get('/debug-user-roles', function () {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        return [
            'user_id' => $user->id,
            'email' => $user->email,
            'email_verified' => $user->hasVerifiedEmail(),
            'email_verified_at' => $user->email_verified_at,
            'roles' => $user->roles->pluck('name'),
            'has_admin_role' => $user->hasRole(config('constants.system_roles.admin')),
            'has_platform_admin_role' => $user->hasRole(config('constants.system_roles.platform_admin')),
            'can_access_panel' => $user->canAccessPanel(Filament::getPanel('admin')),
        ];
    });
}
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/cp_info_sys', function () {
    return phpinfo();
});
require __DIR__.'/auth.php';
