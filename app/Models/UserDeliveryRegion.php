<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Prunable;
use SolutionForest\FilamentAccessManagement\Concerns\FilamentUserHelpers;
use Spatie\Permission\Traits\HasRoles;

class UserDeliveryRegion extends Model
{

    use HasFactory;
    protected $table = 'user_delivery_regions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'country_code',
        'country_region',

    ];

    public function userDeliver()
    {
        return $this->belongsTo(UserDelivery::class, 'user_delivery_id');
    }
}
