<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryVehicle extends Model
{
    protected $table = 'delivery_vehicles';
    protected $fillable = [
        'milage',
        'status',
        'other_status',
        'last_inspection',
        'last_service',
    ];

    protected $casts = [
        'last_inspection' => 'datetime',
        'last_service' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function vehicle() {
        return $this->belongsTo(Vehicle::class);
    }
}
