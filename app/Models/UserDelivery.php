<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserDelivery extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'user_delivery';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'whatsapp',
        'telegram',
        'facebook',
        'instagram',
        'twitter',
        'linkedin',
        'youtube',
        'tiktok',
        'website',
        'about_my_service',
        'profile_image',
        'about_my_service',
        'allow_physical_work',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function regions()
    {
        return $this->hasMany(UserDeliveryRegion::class, 'user_delivery_id');
    }
}
