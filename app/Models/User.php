<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Log;
use SolutionForest\FilamentAccessManagement\Concerns\FilamentUserHelpers;
use Spatie\LaravelPasskeys\Models\Concerns\HasPasskeys;
use Spatie\LaravelPasskeys\Models\Concerns\InteractsWithPasskeys;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasPasskeys
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Prunable, InteractsWithPasskeys, FilamentUserHelpers, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'avatar',
        'google_id',
        'email',
        'password',
        'last_login',
        'is_invited',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login' => 'datetime',
            'is_invited' => 'boolean',
        ];
    }
    public function business()
    {
        return $this->hasOne(Business::class, 'owner_user_id');
    }

    public function supportLanguage()
    {
        return $this->belongsToMany(Language::class, 'user_languages');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        Log::info('canAccessPanel', [
            'user_id' => $this->id,
            'panel_id' => $panel->getId(),
            'has_verified_email' => $this->hasVerifiedEmail(),
            'has_role' => $this->hasRole(UserRole::adminPanelRoles()),
        ]);
        return $this->hasVerifiedEmail()
            && $this->hasRole(UserRole::adminPanelRoles());
    }

    public function delivery()
    {
        return $this->hasOne(UserDelivery::class, 'user_id');
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }
    public function updateSupportLanguage(array $languageIds)
    {
        $this->supportLanguage()->sync($languageIds);
    }
    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::query()->where('created_at', '<=', now()->subYear());
    }
}
