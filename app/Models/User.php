<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;
use SolutionForest\FilamentAccessManagement\Concerns\FilamentUserHelpers;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Prunable, FilamentUserHelpers, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'country_code',
        'country_region',
        'city',
        'address',
        'zip_code',
        'allow_preform_deliveries',
        'apartment_number',
        'building_number',
        'floor_number',
        'dob',
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
            'phone_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'dob' => 'date',
            'floor_number' => 'integer',
            'allow_preform_deliveries' => 'boolean',
            'password' => 'hashed',
        ];
    }
    public function business()
    {
        return $this->hasOne(Business::class, 'owner_user_id');
    }

    public function supportLanguage()
    {
        return $this->belongsToMany(Languages::class, 'user_languages');
    }
    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@wolberg.pro') && $this->hasVerifiedEmail();
    }
    public function deliver()
    {
        return $this->hasOne(UserDelivery::class, 'user_id');
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
